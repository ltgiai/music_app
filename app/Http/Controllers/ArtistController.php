<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\ArtistModel;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    // Chức năng thêm nghệ sĩ
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'ma_tk' => 'required|unique:artist,ma_tk',
            'ten_artist' => 'required|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric'
        ]);

        // Tạo nghệ sĩ mới
        $artist = ArtistModel::create($request->all());

        return response()->json(['message' => 'Tạo artist mới thành công', 'artist' => $artist], 201);
    }

    // Chức năng sửa thông tin nghệ sĩ
    public function update(Request $request, $id)
    {
        // Tìm nghệ sĩ theo ID
        $artist = ArtistModel::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'ten_artist' => 'required|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric'
        ]);

        // Cập nhật thông tin nghệ sĩ
        $artist->update($request->all());

        return response()->json(['message' => 'Artist updated successfully', 'artist' => $artist]);
    }

    // Chức năng xóa nghệ sĩ
    public function destroy($id)
    {
        // Tìm và xóa nghệ sĩ
        $artist = ArtistModel::findOrFail($id);
        $artist->delete();

        return response()->json(['message' => 'Artist deleted successfully']);
    }
}
