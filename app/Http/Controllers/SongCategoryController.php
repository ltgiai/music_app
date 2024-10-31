<?php

namespace App\Http\Controllers;

use App\Models\SongCategoryModel;
use Illuminate\Http\Request;

class SongCategoryController extends Controller
{
    public function index()
    {
        return response()->json(SongCategoryModel::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ten_chung_loai' => 'required|string|max:255', 
        ]);

        $category = SongCategoryModel::create($validatedData);
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = SongCategoryModel::find($id);
        return $category ? response()->json($category) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $category = SongCategoryModel::find($id);
        if (!$category) return response()->json(['message' => 'Not found'], 404);

        $validatedData = $request->validate([
            'ten_chung_loai' => 'string|max:255', 
        ]);

        $category->update($validatedData);
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = SongCategoryModel::find($id);
        if (!$category) return response()->json(['message' => 'Not found'], 404);

        $category->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
