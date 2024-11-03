<?php

namespace App\Http\Controllers;

use App\Models\ArtistModel;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    // Hiển thị danh sách các nghệ sĩ
    public function index()
    {
        $artists = ArtistModel::with(['relationships.tai_khoan', 
                                    'relationships.phieu_rut_tien_artist', 
                                    'relationships.bai_hat_subartist'])->get();
        return response()->json($artists);
    }

    // Tạo mới một nghệ sĩ
    public function store(Request $request)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'ten_artist' => 'required|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric',
        ]);

        // Tạo ID mới cho artist trong controller
        $validatedData['ma_tk'] = $this->generateCustomId();

        // Thêm nghệ sĩ mới
        $artist = (new ArtistModel())->addArtist($validatedData);

        return response()->json($artist, 201); // Trả về HTTP status code 201 (Created)
    }

    // Hiển thị chi tiết một nghệ sĩ
    public function show($id)
    {
        $artist = ArtistModel::with(['relationships.tai_khoan', 'relationships.phieu_rut_tien_artist', 'relationships.bai_hat_subartist'])
                             ->find($id);
        
        if ($artist) {
            return response()->json($artist);
        }
        
        return response()->json(['message' => 'Artist not found'], 404);
    }

    // Cập nhật thông tin một nghệ sĩ
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $validatedData = $request->validate([
            'ma_tk' => 'nullable|string',
            'ten_artist' => 'nullable|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric',
        ]);

        // Cập nhật nghệ sĩ
        $artist = (new ArtistModel())->updateArtist($id, $validatedData);

        if ($artist) {
            return response()->json($artist);
        }

        return response()->json(['message' => 'Artist not found'], 404);
    }

    // Xóa một nghệ sĩ
    public function destroy($id)
    {
        $deleted = (new ArtistModel())->deleteArtist($id);

        if ($deleted) {
            return response()->json(['message' => 'Artist deleted']);
        }

        return response()->json(['message' => 'Artist not found'], 404);
    }

    // Hàm tạo custom ID cho artist
    private function generateCustomId()
    {
        return 'ART' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
