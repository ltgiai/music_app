<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request){
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Lưu file vào thư mục 'public/images' trong storage
            $path = $file->store('public/images');

            // Lấy URL đầy đủ của file đã lưu
            $url = asset(Storage::url($path));

            // Trả về đường dẫn ảnh đã lưu
            return response()->json(['path' => $url]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}