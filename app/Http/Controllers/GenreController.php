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
                    'ma_the_loai' => $item->ma_the_loai,
                    'ten_the_loai' => $item->ten_the_loai
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

    public function getListOfSongsInGenre()
    {
        $songs = DB::table('theloai_baihat')
            ->join('bai_hat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
            ->join('the_loai', 'the_loai.ma_the_loai', '=', 'theloai_baihat.ma_the_loai')
            ->join('tai_khoan', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->select('bai_hat.*', 'the_loai.*')
            ->get();

        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $songs->map(function ($song) {
                return [
                    'ma_the_loai' => $song->ma_the_loai,
                    'ten_the__loai' => $song->ten_the_loai,
                    'ma_bai_hat' => $song->ma_bai_hat,
                    'ten_bai_hat' => $song->ten_bai_hat,
                    // 'artist' => $song->ten_user,
                    'luot_nghe' => $song->luot_nghe,
                    'hinh_anh' => $song->hinh_anh,
                    'ngay_phat_hanh' => $song->ngay_phat_hanh,
                    'trang_thai' => $song->trang_thai
                ];
            }),
            'message' => 'Get all songs successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
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
