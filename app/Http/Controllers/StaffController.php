<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $staffs = Staff::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('staffs.index', compact('staffs'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:staffs',
                'role' => 'required',
                'password' => 'required|min:6',
            ]);

            // Hash password sebelum menyimpan
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);

            Staff::create($data);

            return redirect()->route('staffs.index')
                ->with('success', 'Staff created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('staffs.index')
                ->with('error', 'Failed to create staff: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Staff $staff)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:staffs,email,' . $staff->id_staff . ',id_staff',
                'role' => 'required',
                'password' => 'nullable|min:6',
            ]);

            $data = $request->all();

            // Hash password hanya jika diisi
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Hapus password dari data jika kosong
            }

            $staff->update($data);

            return redirect()->route('staffs.index')
                ->with('success', 'Staff updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('staffs.index')
                ->with('error', 'Failed to update staff: ' . $e->getMessage());
        }
    }

    public function destroy(Staff $staff)
    {
        try {
            $staff->delete();

            return redirect()->route('staffs.index')
                ->with('success', 'Staff deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('staffs.index')
                ->with('error', 'Failed to delete staff: ' . $e->getMessage());
        }
    }
}
