<?php

namespace App\Http\Controllers;

use App\Models\PlaylistModel;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    // Hiển thị danh sách các nghệ sĩ
    public function index()
    {
        $playlists = PlaylistModel::with(['relationships.tai_khoan'])->get();
        return response()->json($playlists);
    }

    // Tạo mới một nghệ sĩ
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

    // Hiển thị chi tiết một nghệ sĩ
    public function show($id)
    {
        $artist = PlaylistModel::with(['relationships.tai_khoan'])
                             ->find($id);
        
        if ($artist) {
            return response()->json($artist);
        }
        
        return response()->json(['message' => 'Playlist not found'], 404);
    }

    // Cập nhật thông tin một nghệ sĩ
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'ma_tk' => 'nullable|string|exists:accounts,ma_tk',
            'ten_artist' => 'nullable|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric',
        ]);

        // Cập nhật nghệ sĩ
        $playlist = (new PlaylistModel())->updatePlaylist($id, $validatedData);

        if ($playlist) {
            return response()->json($playlist);
        }

        return response()->json(['message' => 'Playlist not found'], 404);
    }

    // Xóa một nghệ sĩ
    public function destroy($id)
    {
        $deleted = (new PlaylistModel())->deletePlaylist($id);

        if ($deleted) {
            return response()->json(['message' => 'Playlist deleted']);
        }

        return response()->json(['message' => 'Playlist not found'], 404);
    }

    // Hàm tạo custom ID cho artist
    private function generateCustomId()
    {
        return 'PL' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
