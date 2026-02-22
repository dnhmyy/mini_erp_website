<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('cabang');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('cabang', function($cq) use ($search) {
                      $cq->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        $users = $query->latest()->paginate(30)->appends($request->query());
        
        if ($request->ajax()) {
            return view('users.partials.table', compact('users'))->render();
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $cabangs = Cabang::all();
        return view('users.create', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:superuser,staff_gudang,staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing',
            'cabang_id' => 'required_if:role,staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing|nullable|exists:cabangs,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'cabang_id' => in_array($request->role, ['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry', 'mixing']) ? $request->cabang_id : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $cabangs = Cabang::all();
        return view('users.edit', compact('user', 'cabangs'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:superuser,staff_gudang,staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing',
            'cabang_id' => 'required_if:role,staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing|nullable|exists:cabangs,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'cabang_id' => in_array($request->role, ['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry', 'mixing']) ? $request->cabang_id : null,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
