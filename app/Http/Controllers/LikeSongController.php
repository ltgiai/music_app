<?php

namespace App\Http\Controllers;

use App\Models\LikeSongModel;
use Illuminate\Http\Request;

class LikeSongController extends Controller
{
    public function index()
    {
        return response()->json(LikeSongModel::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'song_id' => 'required|integer',
        ]);

        $songLike = LikeSongModel::create($validatedData);
        return response()->json($songLike, 201);
    }

    public function show($id)
    {
        $songLike = LikeSongModel::find($id);
        return $songLike ? response()->json($songLike) : response()->json(['message' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $songLike = LikeSongModel::find($id);
        if (!$songLike) return response()->json(['message' => 'Not found'], 404);

        $validatedData = $request->validate([
            'user_id' => 'integer',
            'song_id' => 'integer',
        ]);

        $songLike->update($validatedData);
        return response()->json($songLike);
    }

    public function destroy($id)
    {
        $songLike = LikeSongModel::find($id);
        if (!$songLike) return response()->json(['message' => 'Not found'], 404);

        $songLike->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
