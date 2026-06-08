<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Guardian;
use App\Models\User;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::with(['user', 'students.user'])->get();
        return view('admin.guardians.index', compact('guardians'));
    }

    public function create()
    {
        return view('admin.guardians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'nullable|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email',
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'username' => $request->username ?: null,
                'password' => Hash::make($request->password),
                'role' => 'parent',
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ]);

            Guardian::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.guardians.index')->with('success', 'Orang tua berhasil ditambahkan.');
    }

    public function edit(Guardian $guardian)
    {
        $guardian->load('user');
        return view('admin.guardians.edit', compact('guardian'));
    }

    public function update(Request $request, Guardian $guardian)
    {
        $request->validate([
            'username' => 'nullable|string|max:50|unique:users,username,' . $guardian->user_id,
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120|unique:users,email,' . $guardian->user_id,
            'phone' => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $guardian) {
            $userData = [
                'username' => $request->username ?: null,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $guardian->user->update($userData);

            $guardian->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.guardians.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(Guardian $guardian)
    {
        DB::transaction(function () use ($guardian) {
            $guardian->user->delete();
        });

        return redirect()->route('admin.guardians.index')->with('success', 'Orang tua berhasil dihapus.');
    }
}
