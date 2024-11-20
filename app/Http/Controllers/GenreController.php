<?php

namespace App\Http\Controllers;

use App\Models\GenreModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GenreController extends Controller
{
    // Lấy danh sách tất cả thể loại
    public function renderListOfGenres()
    {
        $genres = GenreModel::all();

        if ($genres->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No genres found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $genres->map(function ($genre) {
                return [
                    'ma_the_loai' => $genre->ma_the_loai,
                    'ten_the_loai' => $genre->ten_the_loai,
                ];
            }),
            'message' => 'Genres retrieved successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderListOfSongsInGenre($genreId)
    {
        // Lấy danh sách bài hát thuộc thể loại cụ thể
        $songs = DB::table('theloai_baihat')
            ->join('bai_hat', 'theloai_baihat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->join('the_loai', 'theloai_baihat.ma_the_loai', '=', 'the_loai.ma_the_loai')
            ->where('theloai_baihat.ma_the_loai', $genreId)
            ->select(
                'the_loai.ma_the_loai',
                'the_loai.ten_the_loai',
                'bai_hat.ma_bai_hat',
                'bai_hat.ten_bai_hat',
                'bai_hat.thoi_luong'
            )
            ->get();

        // Kiểm tra nếu không có dữ liệu
        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Không tìm thấy bài hát cho thể loại này',
            ], Response::HTTP_NOT_FOUND);
        }

        // Nhóm bài hát theo thể loại
        $data = [
            'ma_the_loai' => $songs->first()->ma_the_loai,
            'ten_the_loai' => $songs->first()->ten_the_loai,
            'bai_hat' => $songs->map(function ($song) {
                return [
                    'ma_bai_hat' => $song->ma_bai_hat,
                    'ten_bai_hat' => $song->ten_bai_hat,
                    'thoi_luong' => $song->thoi_luong,
                ];
            })->values(),
        ];

        return response()->json([
            'data' => $data,
            'message' => 'Lấy danh sách bài hát theo thể loại thành công',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Lấy thông tin chi tiết của một thể loại
    public function renderGenreDetails($id)
    {
        $genre = GenreModel::find($id);

        if (!$genre) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Genre not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => [
                'ma_the_loai' => $genre->ma_the_loai,
                'ten_the_loai' => $genre->ten_the_loai,
            ],
            'message' => 'Genre details retrieved successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Thêm mới một thể loại
    public function store(Request $request)
    {
        // Validate dữ liệu từ request
        $validatedData = $request->validate([
            'ten_the_loai' => 'required|string|max:255',
        ]);

        $ma_the_loai = 'CATE' . str_pad((int) substr(GenreModel::max('ma_the_loai'), 4) + 1, 4, '0', STR_PAD_LEFT);
        $genre = GenreModel::create([
            'ma_the_loai' => $ma_the_loai,
            'ten_the_loai' => $validatedData['ten_the_loai'],
        ]);

        // Trả về JSON chứa dữ liệu vừa được thêm
        return response()->json([
            'data' => $ma_the_loai,
            'message' => 'Genre created successfully',
            'status' => Response::HTTP_CREATED,
        ], Response::HTTP_CREATED);
    }


    // Xóa thể loại
    public function destroy($id)
    {
        $genre = GenreModel::find($id);

        if (!$genre) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Genre not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $genre->delete();

        return response()->json([
            'message' => 'Genre deleted successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
