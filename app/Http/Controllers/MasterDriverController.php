<?php

namespace App\Http\Controllers;

use App\Models\MasterDriver;
use Illuminate\Http\Request;

class MasterDriverController extends Controller
{
    public function index()
    {
        $drivers = MasterDriver::latest()->paginate(30);
        return view('master_driver.index', compact('drivers'));
    }

    public function create()
    {
        return view('master_driver.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        MasterDriver::create($request->all());
        return redirect()->route('master-driver.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    public function edit(MasterDriver $masterDriver)
    {
        return view('master_driver.edit', compact('masterDriver'));
    }

    public function update(Request $request, MasterDriver $masterDriver)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $masterDriver->update($request->all());
        return redirect()->route('master-driver.index')->with('success', 'Driver berhasil diperbarui.');
    }

    public function destroy(MasterDriver $masterDriver)
    {
        $masterDriver->delete();
        return redirect()->route('master-driver.index')->with('success', 'Driver berhasil dihapus.');
    }
}
