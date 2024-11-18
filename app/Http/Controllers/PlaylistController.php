<?php

namespace App\Http\Controllers;

use App\Models\PlaylistModel;
use Illuminate\Http\Request;
use App\Models\SongModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class PlaylistController extends Controller
{
    // Hiển thị danh sách playlist
    public function renderListOfPlaylists()
    {
        $playlists = DB::table('playlist')
            ->select('playlist.*')
            ->get();

        if ($playlists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $playlists->map(function ($item) {
                return [
                    'ma_playlist' => $item->ma_playlist,
                    'ten_playlist' => $item->ten_playlist,
                ];
            }),
            'message' => 'Get all songs successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderAccountWithPlaylists()
    {
        $accounts = DB::table('tai_khoan')
            ->join('playlist', 'tai_khoan.ma_tk', '=', 'playlist.ma_tk')
            ->join('playlist_baihat', 'playlist_baihat.ma_playlist', '=', 'playlist.ma_playlist')
            ->join('bai_hat', 'playlist_baihat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->select('tai_khoan.ma_tk', 'playlist.ma_playlist', 'playlist.ten_playlist', 'bai_hat.ma_bai_hat', 'bai_hat.ten_bai_hat')
            ->get()
            ->groupBy('ma_tk');

        if ($accounts->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = $accounts->map(function ($playlists, $ma_tk) {
            return [
                'ma_tk' => $ma_tk,
                'playlists' => $playlists->groupBy('ma_playlist')->map(function ($songs, $ma_playlist) {
                    $firstPlaylist = $songs->first(); // Lấy thông tin cơ bản của playlist
                    return [
                        'ma_playlist' => $firstPlaylist->ma_playlist,
                        'ten_playlist' => $firstPlaylist->ten_playlist,
                        'bai_hat' => $songs->map(function ($song) {
                            return [
                                'ma_bai_hat' => $song->ma_bai_hat,
                                'ten_bai_hat' => $song->ten_bai_hat,
                            ];
                        }),
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'data' => $data,
            'message' => 'Get accounts with playlists successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }


    // Tạo mới một playlist
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'ten_playlist' => 'required|string|max:255',
            'ma_tk' => 'required|string|exists:tai_khoan,ma_tk',
            'so_luong_bai_hat' => 'nullable|numeric',
        ]);

        // Tạo ID mới cho artist trong controller
        $validatedData['ma_playlist'] = $this->generateCustomId();

        // Thêm nghệ sĩ mới
        $playlist = (new PlaylistModel())->addPlaylist($validatedData);

        return response()->json($playlist, 201); // Trả về HTTP status code 201 (Created)
    }

    // Hiển thị chi tiết một playlist
    public function show($id)
    {
        $artist = PlaylistModel::with(['relationships.tai_khoan'])
            ->find($id);

        if ($artist) {
            return response()->json($artist);
        }

        return response()->json(['message' => 'Playlist not found'], 404);
    }

    // Cập nhật thông tin một playlist
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'ma_tk' => 'nullable|string|exists:accounts,ma_tk',
            'ten_artist' => 'nullable|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric',
        ]);

        // Cập nhật playlist
        $playlist = (new PlaylistModel())->updatePlaylist($id, $validatedData);

        if ($playlist) {
            return response()->json($playlist);
        }

        return response()->json(['message' => 'Playlist not found'], 404);
    }

    // Xóa một playlist
    public function destroy($id)
    {
        $deleted = (new PlaylistModel())->deletePlaylist($id);

        if ($deleted) {
            return response()->json(['message' => 'Playlist deleted']);
        }

        return response()->json(['message' => 'Playlist not found'], 404);
    }

    // Hàm tạo custom ID cho playlist
    private function generateCustomId()
    {
        return 'PL' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
