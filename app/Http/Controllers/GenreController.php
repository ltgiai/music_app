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
        ]);
        $latestComment = GenreModel::orderBy('ma_the_loai', 'desc')->first();
        if ($latestComment) {
            $lastIdNumber = (int) substr($latestComment->ma_the_loai, 4);
            $newIdNumber = $lastIdNumber + 1;
            $newId = 'CATE' . str_pad($newIdNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'CATE0001';
        }

        $validatedData['ma_the_loai'] = $newId;

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
