<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(){
        $units = Unit::all();
        return response()->json([
            'message' => 'berhasil menampilkan data',
            'data' => $units,
        ],201);
    }

    public function show($id){
        $unit = Unit::findOrFail($id);
        return response()->json([
            'message' => 'berhasil menampilkan data dengan id ' . $id,
            'data' => $unit,
        ], 201);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max;255',
        ]);

        $unit = Unit::create([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'berhasil menambah data unit',
            'data' => $unit
        ], 201);
    }

    public function destroy($id){
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
            'data' => $unit,
        ], 201);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'name' => 'required|string|max;255',
        ]);
        $unit = Unit::findOrFail($id);
        $unit->update($validated);
        return response()->json([
            'message' => 'berhasil memperbarui data',
            'data' => $unit,
        ], 201);
    }
}
