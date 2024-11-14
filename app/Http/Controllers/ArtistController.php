namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ArtistModel;
use Symfony\Component\HttpFoundation\Response;
use Log;

class ArtistController extends Controller
{
    // Admin muốn xem toàn bộ danh sách nghệ sĩ
    public function index()
    {
        $artists = DB::table('artist')
            ->join('tai_khoan', 'artist.ma_tk', '=', 'tai_khoan.ma_tk')
            ->select('artist.*', 'tai_khoan.gmail')
            ->get();

        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist list is empty'
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                'artists' => $artists->map(function ($artist) {
                    return [
                        'ma_tk' => $artist->ma_tk,
                        'ten_artist' => $artist->ten_artist,
                        'anh_dai_dien' => $artist->anh_dai_dien,
                        'tong_tien' => $artist->tong_tien,
                        'gmail' => $artist->gmail
                    ];
                }),
                'message' => 'Get all artists successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        }
    }

    // Lưu trữ nghệ sĩ mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ma_tk' => 'required|exists:tai_khoan,ma_tk',
            'ten_artist' => 'required',
            'anh_dai_dien' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            ArtistModel::create([
                'ma_tk' => $request->ma_tk,
                'ten_artist' => $request->ten_artist,
                'anh_dai_dien' => $request->anh_dai_dien,
                'tong_tien' => 0, // Mặc định là 0 khi tạo mới
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Artist created successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Artist created failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Artist creation failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Hiển thị thông tin một nghệ sĩ theo mã tài khoản
    public function show($ma_tk)
    {
        $artist = ArtistModel::where('ma_tk', $ma_tk)->first();

        if ($artist) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => [
                    'ma_tk' => $artist->ma_tk,
                    'ten_artist' => $artist->ten_artist,
                    'anh_dai_dien' => $artist->anh_dai_dien,
                    'tong_tien' => $artist->tong_tien,
                ],
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    // Cập nhật thông tin nghệ sĩ
    public function update(Request $request, $ma_tk)
    {
        $artist = ArtistModel::find($ma_tk);
        if (!$artist) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'ten_artist' => 'required',
            'anh_dai_dien' => 'required',
            'tong_tien' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $artist->update([
                'ten_artist' => $request->ten_artist,
                'anh_dai_dien' => $request->anh_dai_dien,
                'tong_tien' => $request->tong_tien,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Artist updated successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Artist update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Artist update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Xóa nghệ sĩ bằng cách cập nhật trạng thái
    public function destroy($ma_tk)
    {
        $artist = ArtistModel::find($ma_tk);
        if (!$artist) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Artist not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $artist->delete(); // Xóa nghệ sĩ

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Artist deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Artist deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Artist deletion failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
