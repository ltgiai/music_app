<?php

namespace App\Http\Controllers;

use App\Models\LikeAlbumModel;

use Illuminate\Http\Response;

class LikeAlbumController extends Controller {
    public function index() {
        $likeAlbum = LikeAlbumModel::all();
        if (!$likeAlbum) {
            return response()->json([
                'message' => 'Sorry, like album not found',
                'status' => Response::HTTP_NOT_FOUND
            ],  Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'data' => $likeAlbum,
                'status' => Response::HTTP_OK   
            ], Response::HTTP_OK);
        }
    }

    public function accountLikesAlbums($ma_tk) {
        $likeAlbum = LikeAlbumModel::where('ma_tk', $ma_tk)->get();
        if (!$likeAlbum) {
            return response()->json([
                'message' => 'Sorry, like album not found',
                'status' => Response::HTTP_NOT_FOUND
            ],  Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'data' => $likeAlbum,
                'status' => Response::HTTP_OK   
            ], Response::HTTP_OK);
        }
    }
}
