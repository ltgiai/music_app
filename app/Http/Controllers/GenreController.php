<?php

namespace App\Http\Controllers;

use App\Models\GenreModel;
use App\Models\SongModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends Controller
{
    public function getListOfGenres()
    {
        $genre = DB::table('the_loai')
            ->select('the_loai.*')
            ->get();

        if ($genre->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $genre->map(function ($item) {
                return [
                    'ma_bai_hat' => $item->ma_the_loai,
                    'ten_bai_hat' => $item->ten_the_loai
                ];
            }),
            'message' => 'Get all genre successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ten_the_loai' => 'required|string|max:255',
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
