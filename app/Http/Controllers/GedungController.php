<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $data = Gedung::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gedung_kode' => 'required|max:10',
            'gedung_nama' => 'required|max:100',
        ]);

        $gedung = Gedung::create($validated);
        return response()->json($gedung, 201);
    }

    public function show($id)
    {
        $gedung = Gedung::findOrFail($id);
        return response()->json($gedung);
    }

    public function update(Request $request, $id)
    {
        $gedung = Gedung::findOrFail($id);
        $validated = $request->validate([
            'gedung_kode' => 'required|max:10',
            'gedung_nama' => 'required|max:100',
        ]);

        $gedung->update($validated);
        return response()->json($gedung);
    }

    public function destroy($id)
    {
        $gedung = Gedung::findOrFail($id);
        $gedung->delete();
        return response()->json(['message' => 'Gedung deleted']);
    }
}
