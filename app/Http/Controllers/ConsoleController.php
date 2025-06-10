<?php

namespace App\Http\Controllers;

use App\Models\Console;
use Illuminate\Http\Request;

class ConsoleController extends Controller
{
    /**
     * Menampilkan daftar console.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            $consoles = Console::when($search, function ($query, $search) {
                return $query->where('consoleRoom', 'like', '%' . $search . '%')
                    ->orWhere('typeConsole', 'like', '%' . $search . '%')
                    ->orWhere('availability', 'like', '%' . $search . '%');
            })->paginate(10);

            return view('consoles.index', compact('consoles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form create console.
     */
    public function create()
    {
        try {
            return view('consoles.create');
        } catch (\Exception $e) {
            return redirect()->route('consoles.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyimpan console baru ke database.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'typeConsole' => 'required',
                'consoleRoom' => 'required',
                'availability' => 'required',
                'price' => 'required|numeric|min:0',
            ]);

            // Simpan data console
            Console::create($request->all());

            return redirect()->route('consoles.index')->with('success', 'Console berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->route('consoles.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail console.
     */
    public function show(Console $console)
    {
        try {
            return view('consoles.show', compact('console'));
        } catch (\Exception $e) {
            return redirect()->route('consoles.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit console.
     */
    public function edit(Console $console)
    {
        try {
            return view('consoles.edit', compact('console'));
        } catch (\Exception $e) {
            return redirect()->route('consoles.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mengupdate console yang sudah ada.
     */
    public function update(Request $request, $id_console)
    {
        try {
            // Validasi input
            $request->validate([
                'typeConsole'   => 'required|in:PS 5,PS 4,PS 3',
                'consoleRoom'   => 'required|string|max:255', // ✅ Perbaiki di sini
                'availability'  => 'required|in:Ready,Not Yet', // ✅ Validasi untuk status
                'price'         => 'required|numeric|min:0',
            ]);

            // Cari console berdasarkan id_console
            $console = Console::findOrFail($id_console);

            // Update data console
            $console->update([
                'typeConsole'   => $request->typeConsole,
                'consoleRoom'   => $request->consoleRoom,
                'availability'  => $request->availability,
                'price'         => $request->price,
            ]);

            return redirect()->route('consoles.index')->with('success', 'Console berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('consoles.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Menghapus console.
     */
    public function destroy(Console $console)
    {
        try {
            // Hapus console
            $console->delete();

            return redirect()->route('consoles.index')->with('success', 'Console berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('consoles.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
