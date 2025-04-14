<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'tampilkan data',
            'data' => $categories,
        ], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'message' => 'Berhasil menampilkan data dengan id ' . $id,
            'data' => $category,
        ], 201);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $category = Category::create(['name' => $validated['name']]);
        return response()->json([
            'message' => 'data berhasil dibuat',
            'data' => $category,
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate(['name' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update($validated);
        return response()->json([
            'message' => 'Data kategori berhasil diperbarui',
            'data' => $category,
        ], 201);
    }
}
