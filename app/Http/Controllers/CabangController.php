<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cabang;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $query = Cabang::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%');
        }
        $cabangs = $query->latest()->paginate(30)->appends($request->query());
        return view('cabang.index', compact('cabangs'));
    }

    public function create()
    {
        return view('cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable',
        ]);

        Cabang::create($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Cabang $cabang)
    {
        return view('cabang.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable',
        ]);

        $cabang->update($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->delete();
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
