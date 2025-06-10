<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Console;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('console');

        // Search text (customer, phone, console type)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%")
                    ->orWhereHas('console', function ($cq) use ($search) {
                        $cq->where('typeConsole', 'like', "%$search%");
                    });
            });
        }

        // Filter by booking date
        if ($request->filled('filter_date')) {
            $query->whereDate('booking_date', $request->filter_date);
        }

        // Filter by booking status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // Filter by console type
        if ($request->filled('filter_console')) {
            $query->whereHas('console', function ($q) use ($request) {
                $q->where('typeConsole', $request->filter_console);
            });
        }

        $bookings = $query->orderByDesc('booking_date')->paginate(10);

        $consoles = Console::select('typeConsole')->distinct()->get();

        return view('bookings.index', [
            'bookings' => $bookings,
            'consoles' => $consoles,
            'alert' => session('alert'),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $query = Booking::with('console');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%")
                    ->orWhereHas('console', function ($cq) use ($search) {
                        $cq->where('typeConsole', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('filter_date')) {
            $query->whereDate('booking_date', $request->filter_date);
        }

        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        if ($request->filled('filter_console')) {
            // Filter berdasarkan console id (sesuai input filter_console)
            $query->where('console_id', $request->filter_console);
        }

        $bookings = $query->get();

        $filename = 'data-booking-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Customer',
                'Telepon',
                'Tanggal',
                'Console',
                'Waktu',
                'Durasi',
                'Total',
                'Status',
                'Status Bermain',
            ]);

            foreach ($bookings as $booking) {
                fputcsv($handle, [
                    $booking->customer_name,
                    $booking->phone_number,
                    $booking->booking_date->format('d/m/Y'),
                    $booking->console ? $booking->console->typeConsole . ' (' . $booking->console->consoleRoom . ')' : '-',
                    $booking->start_time . ' - ' . $booking->end_time,
                    $booking->estimated_hours . ' jam',
                    $booking->total_price,
                    $booking->status,
                    $booking->playing_status, // tambahkan status bermain
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $consoles = Console::available()->get();
        $timeSlots = $this->generateTimeSlots();
        return view('bookings.create', compact('consoles', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^[+]?[0-9]{1,4}[.\-\s]?[0-9]+$/'],
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string',
            'estimated_hours' => 'required|integer|min:1|max:8',
            'console_id' => 'required|exists:consoles,id_console',
            'selected_games' => 'required|array',
            'payment_type' => 'required|in:Cash,Transfer,QRIS',
            'playing_status' => 'required|in:Play,Not Play',
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = (clone $start)->addHours((int) $validated['estimated_hours']);

        // Cek booking bentrok dengan status bermain Play
        $isBooked = Booking::where('console_id', $validated['console_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('playing_status', 'Play')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start->format('H:i'), $end->format('H:i')])
                    ->orWhereBetween('end_time', [$start->format('H:i'), $end->format('H:i')])
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->where('start_time', '<=', $start->format('H:i'))
                            ->where('end_time', '>=', $end->format('H:i'));
                    });
            })
            ->exists();

        if ($isBooked) {
            return back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'in use, please select another time.',
            ]);
        }

        try {
            DB::beginTransaction();

            $console = Console::findOrFail($validated['console_id']);
            $validated['end_time'] = $end->format('H:i');
            $validated['total_price'] = $console->price * $validated['estimated_hours'];
            $validated['status'] = 'pending';
            $validated['selected_games'] = json_encode($validated['selected_games']);

            Booking::create($validated);

            DB::commit();

            return redirect()->route('bookings.index')->with('alert', [
                'type' => 'success',
                'message' => 'Booking berhasil dibuat!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ]);
        }
    }

    private function calculateEndTime($start_time, $estimated_hours)
    {
        $start = Carbon::createFromFormat('H:i', $start_time);
        $end = $start->addHours((int) $estimated_hours);
        return $end->format('H:i');
    }

    public function edit(Booking $booking)
    {
        $consoles = Console::available()->get();
        $timeSlots = $this->generateTimeSlots();

        return view('bookings.edit', compact('booking', 'consoles', 'timeSlots'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'max:20', 'regex:/^[+]?[0-9]{1,4}[.\-\s]?[0-9]+$/'],
            'booking_date' => 'required|date',
            'start_time' => 'required|string',
            'estimated_hours' => 'required|integer|min:1|max:8',
            'console_id' => 'required|exists:consoles,id_console',
            'selected_games' => 'required|array',
            'payment_type' => 'required|in:Cash,Transfer,QRIS',
            'status' => 'required|in:pending,success,canceled',
            'playing_status' => 'required|in:Play,Not Play',
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = (clone $start)->addHours((int) $validated['estimated_hours']);

        // Cek bentrok kecuali booking sendiri, dengan status bermain Play
        $isBooked = Booking::where('console_id', $validated['console_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('id_booking', '!=', $booking->id_booking)
            ->where('playing_status', 'Play')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start->format('H:i'), $end->format('H:i')])
                    ->orWhereBetween('end_time', [$start->format('H:i'), $end->format('H:i')])
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->where('start_time', '<=', $start->format('H:i'))
                            ->where('end_time', '>=', $end->format('H:i'));
                    });
            })
            ->exists();

        if ($isBooked) {
            return back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'in use, please select another time.',
            ]);
        }

        try {
            DB::beginTransaction();

            $console = Console::findOrFail($validated['console_id']);
            $validated['end_time'] = $end->format('H:i');
            $validated['total_price'] = $console->price * $validated['estimated_hours'];
            $validated['selected_games'] = json_encode($validated['selected_games']);

            $booking->update($validated);

            DB::commit();

            return redirect()->route('bookings.index')->with('alert', [
                'type' => 'success',
                'message' => 'Booking berhasil diperbarui!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('alert', [
                'type' => 'danger',
                'message' => 'Gagal memperbarui: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Update console availability sebelum hapus booking
            Console::where('id_console', $booking->console_id)
                ->update(['availability' => 'Ready']);
            $booking->delete();

            DB::commit();

            return redirect()->route('bookings.index')->with('alert', [
                'type' => 'success',
                'message' => 'Booking berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bookings.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Gagal menghapus: ' . $e->getMessage(),
            ]);
        }
    }

    private function generateTimeSlots()
    {
        return array_map(function ($h) {
            return str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        }, range(10, 22));
    }
}
