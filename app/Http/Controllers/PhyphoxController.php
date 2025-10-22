<?php

namespace App\Http\Controllers;

use App\Models\Phyphox;
use Illuminate\Http\Request;

class PhyphoxController extends Controller
{
    public function index()
    {
        $phyphoxes = Phyphox::all();
        return view('phyphox.index', compact('phyphoxes'));
    }

    public function create()
    {
        return view('phyphox.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'is_active' => 'in:0,1',
        ]);

        Phyphox::create($request->all());

        return redirect()->route('phyphox.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(Phyphox $phyphox)
    {
        return view('phyphox.show', compact('phyphox'));
    }

    public function edit(Phyphox $phyphox)
    {
        return view('phyphox.edit', compact('phyphox'));
    }

    public function update(Request $request, Phyphox $phyphox)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'is_active' => 'in:0,1',
        ]);

        $phyphox->update($request->all());

        return redirect()->route('phyphox.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Phyphox $phyphox)
    {
        $phyphox->delete();

        return redirect()->route('phyphox.index')->with('success', 'Data berhasil dihapus.');
    }
}
