<?php

namespace App\Http\Controllers;

use App\Models\GenreModel;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        return response()->json(GenreModel::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ten_the_loai' => 'required|string|max:255',
            'ma_chung_loai' => 'required|integer|exists:chung_loai,ma_chung_loai', 
        ]);

        $genre = GenreModel::create($validatedData);
        return response()->json($genre, 201);
    }

    public function show($id)
    {
        $genre = GenreModel::find($id);
        return $genre ? response()->json($genre) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $genre = GenreModel::find($id);
        if (!$genre) return response()->json(['message' => 'Not found'], 404);

        $validatedData = $request->validate([
            'ten_the_loai' => 'string|max:255',
            'ma_chung_loai' => 'integer|exists:song_categories,ma_chung_loai',
        ]);

        $genre->update($validatedData);
        return response()->json($genre);
    }

    public function destroy($id)
    {
        $genre = GenreModel::find($id);
        if (!$genre) return response()->json(['message' => 'Not found'], 404);

        $genre->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
