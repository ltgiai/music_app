<?php

namespace App\Http\Controllers;

use App\Models\PlaylistModel;
use Illuminate\Http\Request;
use App\Models\SongModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\String_;
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

    public function renderPlaylistByAccount($ma_tk)
    {
        // Lấy dữ liệu tài khoản cùng playlist, bài hát và album liên quan
        $playlists = DB::table('tai_khoan')
            ->join('playlist', 'tai_khoan.ma_tk', '=', 'playlist.ma_tk')
            ->leftJoin('playlist_baihat', 'playlist_baihat.ma_playlist', '=', 'playlist.ma_playlist')
            ->leftJoin('bai_hat', 'playlist_baihat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->leftJoin('album', 'bai_hat.ma_album', '=', 'album.ma_album') // Thêm thông tin album
            ->select(
                'tai_khoan.ma_tk',
                'playlist.ma_playlist',
                'playlist.ten_playlist',
                'playlist.hinh_anh',
                'bai_hat.ma_bai_hat',
                'bai_hat.ten_bai_hat',
                'bai_hat.thoi_luong',
                'bai_hat.ngay_phat_hanh',
                'album.ma_album',
                'album.ten_album'
            )
            ->where('tai_khoan.ma_tk', '=', $ma_tk) // Lọc theo tài khoản
            ->get()
            ->groupBy('ma_playlist'); // Nhóm theo playlist

        // Kiểm tra nếu không có dữ liệu
        if ($playlists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No playlists or songs found for this account',
            ], Response::HTTP_NOT_FOUND);
        }

        // Xử lý và tạo cấu trúc JSON
        $data = $playlists->map(function ($songs, $ma_playlist) {
            $playlistInfo = $songs->first(); // Lấy thông tin playlist từ bài hát đầu tiên
            if (!$playlistInfo) {
                return null; // Tránh lỗi nếu không có bài hát nào
            }

            return [
                'ma_tk' => $playlistInfo->ma_tk,
                'ma_playlist' => $ma_playlist,
                'ten_playlist' => $playlistInfo->ten_playlist,
                'hinh_anh' => $playlistInfo->hinh_anh,
                'bai_hat' => $songs->filter(function ($song) {
                    return $song->ma_bai_hat !== null; // Loại bỏ playlist không có bài hát
                })->map(function ($song) {
                    return [
                        'ma_bai_hat' => $song->ma_bai_hat,
                        'ten_bai_hat' => $song->ten_bai_hat,
                        'thoi_luong' => $song->thoi_luong,
                        'ngay_phat_hanh' => $song->ngay_phat_hanh,
                        'album' => [
                            'ma_album' => $song->ma_album,
                            'ten_album' => $song->ten_album,
                        ],
                    ];
                })->values(),
            ];
        })->filter()->values(); // Loại bỏ null và sắp xếp lại chỉ mục

        return response()->json([
            'data' => $data,
            'message' => 'Get playlists with songs successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderPlaylistByID($ma_playlist)
    {
        // Lấy dữ liệu tài khoản cùng playlist, bài hát và album liên quan
        $playlist = DB::table('playlist')
            ->leftJoin('playlist_baihat', 'playlist_baihat.ma_playlist', '=', 'playlist.ma_playlist')
            ->leftJoin('bai_hat', 'playlist_baihat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->leftJoin('album', 'bai_hat.ma_album', '=', 'album.ma_album') // Thêm thông tin album
            ->select(
                'playlist.ma_playlist',
                'playlist.ten_playlist',
                'playlist.hinh_anh',
                'bai_hat.ma_bai_hat',
                'bai_hat.ten_bai_hat',
                'bai_hat.thoi_luong',
                'bai_hat.ngay_phat_hanh',
                'album.ma_album',
                'album.ten_album'
            )
            ->where('playlist.ma_playlist', '=', $ma_playlist) // Lọc theo playlist
            ->get()
            ->groupBy('ma_playlist'); // Nhóm theo playlist

        // Kiểm tra nếu không có dữ liệu
        if ($playlist->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No playlists or songs found for this account',
            ], Response::HTTP_NOT_FOUND);
        }

        // Xử lý và tạo cấu trúc JSON
        $data = $playlist->map(function ($songs, $ma_playlist) {
            $playlistInfo = $songs->first(); // Lấy thông tin playlist từ bài hát đầu tiên
            if (!$playlistInfo) {
                return null; // Tránh lỗi nếu không có bài hát nào
            }

            return [
                'ma_playlist' => $ma_playlist,
                'ten_playlist' => $playlistInfo->ten_playlist,
                'hinh_anh' => $playlistInfo->hinh_anh,
                'bai_hat' => $songs->filter(function ($song) {
                    return $song->ma_bai_hat !== null; // Loại bỏ playlist không có bài hát
                })->map(function ($song) {
                    return [
                        'ma_bai_hat' => $song->ma_bai_hat,
                        'ten_bai_hat' => $song->ten_bai_hat,
                        'thoi_luong' => $song->thoi_luong,
                        'ngay_phat_hanh' => $song->ngay_phat_hanh,
                        'album' => [
                            'ma_album' => $song->ma_album,
                            'ten_album' => $song->ten_album,
                        ],
                    ];
                })->values(),
            ];
        })->filter()->values(); // Loại bỏ null và sắp xếp lại chỉ mục

        return response()->json([
            'data' => $data,
            'message' => 'Get playlists with songs successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        // Lấy dữ liệu từ request
        $data = $request->only(['ma_tk', 'ma_bai_hat', 'ma_playlist']);

        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($data, [
            'ma_tk' => 'required|string|exists:tai_khoan,ma_tk',
            'ma_bai_hat' => 'required|string|exists:bai_hat,ma_bai_hat',
            'ma_playlist' => 'nullable|string|exists:playlist,ma_playlist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        // Nếu có ma_playlist -> Thêm bài hát vào playlist có sẵn
        if (!empty($data['ma_playlist'])) {
            // Kiểm tra quyền sở hữu playlist
            $playlist = DB::table('playlist')
                ->where('ma_playlist', $data['ma_playlist'])
                ->where('ma_tk', $data['ma_tk'])
                ->first();

            if (!$playlist) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Playlist not found or does not belong to this account',
                ], Response::HTTP_NOT_FOUND);
            }

            // Thêm bài hát vào bảng playlist_baihat
            DB::table('playlist_baihat')->insert([
                'ma_playlist' => $data['ma_playlist'],
                'ma_bai_hat' => $data['ma_bai_hat'],
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song added to existing playlist successfully',
            ], Response::HTTP_OK);
        }

        // Nếu không có ma_playlist -> Tạo mới playlist
        try {
            $latestPlaylist = DB::table('playlist')
                ->where('ma_tk', $data['ma_tk'])
                ->latest('ma_playlist')
                ->first();

            // Đặt tên cho playlist mới
            $newPlaylistName = 'Danh sách phát của tôi #' .
                ($latestPlaylist ? intval(preg_replace('/[^0-9]/', '', $latestPlaylist->ten_playlist)) + 1 : 1);

            // Tạo mã playlist mới
            $newPlaylistId = 'PL' . str_pad(
                (int) filter_var(DB::table('playlist')->max('ma_playlist'), FILTER_SANITIZE_NUMBER_INT) + 1, 4, '0', STR_PAD_LEFT);

            // Thêm playlist mới
            DB::table('playlist')->insert([
                'ma_playlist' => $newPlaylistId,
                'ma_tk' => $data['ma_tk'],
                'ten_playlist' => $newPlaylistName,
                'hinh_anh' => null, // Có thể truyền hình ảnh mặc định nếu cần
            ]);

            // Thêm bài hát vào playlist mới
            DB::table('playlist_baihat')->insert([
                'ma_playlist' => $newPlaylistId,
                'ma_bai_hat' => $data['ma_bai_hat'],
            ]);

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'Playlist created and song added successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Error creating playlist: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to create playlist or add song',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
    public function update(Request $request, $ma_playlist)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'ten_playlist' => 'nullable|string|max:255',
            'so_luong_bai_hat' => 'nullable|numeric',
            'hinh_anh' => 'nullable|string',
            'ma_tk' => 'nullable|string|exists:tai_khoan,ma_tk',
        ]);

        // Kiểm tra lỗi validate
        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        // Dữ liệu đã được validate
        $validatedData = $validator->validated();

        try {
            // Tìm playlist theo mã
            $playlist = PlaylistModel::where('ma_playlist', $ma_playlist)->first();

            if (!$playlist) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Playlist not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Cập nhật playlist
            $playlist->update($validatedData);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Playlist updated successfully',
                'data' => $playlist,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Ghi log lỗi nếu có
            Log::error('Error updating playlist: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to update playlist',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Xóa toàn bộ playlist
    public function deletePlaylist($ma_tai_khoan, $ma_playlist)
    {
        try {
            // Kiểm tra playlist thuộc tài khoản
            $playlistOwner = DB::table('playlist')
                ->where('ma_playlist', $ma_playlist)
                ->where('ma_tk', $ma_tai_khoan)
                ->exists();

            if (!$playlistOwner) {
                return response()->json([
                    'status' => Response::HTTP_FORBIDDEN,
                    'message' => 'Playlist does not belong to this account',
                ], Response::HTTP_FORBIDDEN);
            }

            // Xóa các bài hát trong playlist
            DB::table('playlist_baihat')
                ->where('ma_playlist', $ma_playlist)
                ->delete();

            // Xóa playlist
            $deleted = DB::table('playlist')
                ->where('ma_playlist', $ma_playlist)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Playlist deleted successfully',
                ], Response::HTTP_OK);
            }

            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Playlist not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error deleting playlist: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to delete playlist',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Xóa bài hát trong playlist của một tài khoản
    public function deleteSongFromPlaylist($ma_tai_khoan, $ma_playlist, $ma_bai_hat)
    {
        try {
            // Kiểm tra playlist thuộc tài khoản
            $playlistOwner = DB::table('playlist')
                ->where('ma_playlist', $ma_playlist)
                ->where('ma_tk', $ma_tai_khoan)
                ->exists();

            if (!$playlistOwner) {
                return response()->json([
                    'status' => Response::HTTP_FORBIDDEN,
                    'message' => 'Playlist does not belong to this account',
                ], Response::HTTP_FORBIDDEN);
            }

            // Xóa bài hát khỏi playlist
            $deleted = DB::table('playlist_baihat')
                ->where('ma_playlist', $ma_playlist)
                ->where('ma_bai_hat', $ma_bai_hat)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Song removed from playlist successfully',
                ], Response::HTTP_OK);
            }

            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found in the specified playlist',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error deleting song from playlist: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to remove song from playlist',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
