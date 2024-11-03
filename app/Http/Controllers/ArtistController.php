<?php

namespace App\Http\Controllers;

use App\Models\ArtistModel;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    // Phương thức lấy danh sách tất cả nghệ sĩ
    public function index()
    {
        // Lấy tất cả các nghệ sĩ
        $artists = ArtistModel::all();

        // Trả về dữ liệu dưới dạng JSON cho front-end
        return response()->json($artists);
    }

    // Phương thức lấy chi tiết một nghệ sĩ theo id
    public function show($id)
    {
        // Tìm nghệ sĩ theo id
        $artist = ArtistModel::find($id);

        // Kiểm tra nếu nghệ sĩ không tồn tại
        if (!$artist) {
            return response()->json(['message' => 'Artist not found'], 404);
        }

        // Lấy các mối quan hệ của nghệ sĩ
        $relationships = $artist->relationships();

        // Kết hợp dữ liệu nghệ sĩ và các mối quan hệ (ví dụ: tài khoản)
        $data = [
            'artist' => $artist,
            'account' => $relationships['account'],
            // Thêm các quan hệ khác nếu cần
        ];

        // Trả về dữ liệu JSON cho front-end
        return response()->json($data);
    }

    // Phương thức thêm nghệ sĩ mới
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào từ request
        $validated = $request->validate([
            'ma_tk' => 'required|exists:accounts,ma_tk',
            'ten_artist' => 'required|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric|min:0',
        ]);

        // Tạo nghệ sĩ mới
        $artist = ArtistModel::create($validated);

        // Trả về dữ liệu nghệ sĩ vừa tạo
        return response()->json($artist, 201);
    }

    // Phương thức cập nhật thông tin nghệ sĩ
    public function update(Request $request, $id)
    {
        // Tìm nghệ sĩ theo id
        $artist = ArtistModel::find($id);

        // Kiểm tra nếu nghệ sĩ không tồn tại
        if (!$artist) {
            return response()->json(['message' => 'Artist not found'], 404);
        }

        // Validate dữ liệu đầu vào từ request
        $validated = $request->validate([
            'ma_tk' => 'sometimes|exists:accounts,ma_tk',
            'ten_artist' => 'sometimes|string|max:255',
            'anh_dai_dien' => 'nullable|string',
            'tong_tien' => 'nullable|numeric|min:0',
        ]);

        // Cập nhật nghệ sĩ với dữ liệu đã validate
        $artist->update($validated);

        // Trả về dữ liệu nghệ sĩ đã cập nhật
        return response()->json($artist);
    }

    // Phương thức xóa nghệ sĩ
    public function destroy($id)
    {
        // Tìm nghệ sĩ theo id
        $artist = ArtistModel::find($id);

        // Kiểm tra nếu nghệ sĩ không tồn tại
        if (!$artist) {
            return response()->json(['message' => 'Artist not found'], 404);
        }

        // Xóa nghệ sĩ
        $artist->delete();

        // Trả về thông báo đã xóa
        return response()->json(['message' => 'Artist deleted']);
    }
}
