<?php

namespace App\Http\Controllers;

use App\Models\GenreSongModel;
use Illuminate\Http\Request;

class GenreSongController extends Controller
{
    public function index()
    {
        return response()->json(GenreSongModel::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'genre_id' => 'required|integer',
            'song_id' => 'required|integer',
        ]);

        $genreSong = GenreSongModel::create($validatedData);
        return response()->json($genreSong, 201);
    }

    public function show($id)
    {
        $genreSong = GenreSongModel::find($id);
        return $genreSong ? response()->json($genreSong) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $genreSong = GenreSongModel::find($id);
        if (!$genreSong) return response()->json(['message' => 'Not found'], 404);

        $validatedData = $request->validate([
            'genre_id' => 'integer',
            'song_id' => 'integer',
        ]);

        $genreSong->update($validatedData);
        return response()->json($genreSong);
    }

    public function destroy($id)
    {
        $genreSong = GenreSongModel::find($id);
        if (!$genreSong) return response()->json(['message' => 'Not found'], 404);

        $genreSong->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
