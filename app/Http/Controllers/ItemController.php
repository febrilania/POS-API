<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){
        $items = Item::all();
        return response()->json([
            'message' => 'berhasil menampilkan data',
            'data' => $items,
        ], 201);
    }

    public function show($id){
        $item = Item::findOrFail($id);
        return response()->json([
            'message' => 'berhasil menampilkan data dengan id ' . $id,
            'data' => $item,
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'unit_id' => 'required',
            'category_id' => 'nullable',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string',
            'price' => 'required',
            'stock' => 'required|integer',
        ]);

        $item = Item::create([
            'unit_id' => $validated['unit_id'],
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'barcode' => $validated['barcode'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        return response()->json([
            'message' => 'berhasil menambah data',
            'data' => $item,
        ], 201);
    }

    public function destroy($id){
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'unit_id' => 'required',
            'category_id' => 'nullable',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string',
            'price' => 'required',
            'stock' => 'required|integer',
        ]);

        $item = Item::findOrFail($id);
        $item->update($validated);
        return response()->json([
            'message' => 'berhasil memperbarui data',
            'data' => $item,
        ]);
    }
}
