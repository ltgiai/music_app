<?php

namespace App\Http\Controllers;

use App\Models\AdvertisementModel;
use App\Models\AdvertisingContractModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


/*  
    Các trạng thái của quảng cáo:
        - 1: Đang hoạt động, đã tạo nhưng chưa có hợp đồng
        - 0: Đã xóa, hết hợp đồng

    Lượt phát tích lũy là 0 và trạng thái là 1 hoặc là mới tạo hoặc là xài hết lượt phát

    Quảng cáo khi tạo phải có mã nhà quảng cáo

    Quảng cáo khi mới tạo chưa có hợp đồng gì thì có thể xóa được

    Chỉ có thể chỉnh sửa tên quảng cáo 
*/

class AdvertisementController extends Controller
{
    public function index() //checked
    {
        $advertisements = DB::table('quang_cao')
            ->join('nha_dang_ky_quang_cao', 'quang_cao.ma_nqc', '=', 'nha_dang_ky_quang_cao.ma_nqc')
            ->select('quang_cao.*', 'nha_dang_ky_quang_cao.ten_nqc', 'nha_dang_ky_quang_cao.so_dien_thoai')
            ->where('quang_cao.trang_thai', '=', 1)
            ->get();
        if ($advertisements->isEmpty()) {
            return response()->json([
                'message' => 'No advertisement found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "advertisements" => $advertisements->map(function ($ad) {
                    return [
                        'ma_quang_cao' => $ad->ma_quang_cao,
                        'ten_quang_cao' => $ad->ten_quang_cao,
                        'ngay_tao' => $ad->ngay_tao,
                        'luot_phat_tich_luy' => $ad->luot_phat_tich_luy,
                        'hinh_anh' => $ad->hinh_anh,
                        'trang_thai' => $ad->trang_thai,
                        'ma_nqc' => $ad->ma_nqc,
                        'ten_nqc' => $ad->ten_nqc,
                        'sdt' => $ad->so_dien_thoai
                    ];
                }),
                'message' => 'Get all advertisement successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function show($id) //checked
    {
        $advertisement = DB::table('quang_cao')
            ->join('nha_dang_ky_quang_cao', 'quang_cao.ma_nqc', '=', 'nha_dang_ky_quang_cao.ma_nqc')
            ->select('quang_cao.*', 'nha_dang_ky_quang_cao.ten_nqc', 'nha_dang_ky_quang_cao.so_dien_thoai')
            ->where('quang_cao.ma_quang_cao', '=', $id)
            ->where('quang_cao.trang_thai', '=', 1)
            ->first();

        if (!$advertisement) {
            return response()->json([
                'message' => 'Advertisement not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "data" => [
                    'ma_quang_cao' => $advertisement->ma_quang_cao,
                    'ten_quang_cao' => $advertisement->ten_quang_cao,
                    'ngay_tao' => $advertisement->ngay_tao,
                    'luot_phat_tich_luy' => $advertisement->luot_phat_tich_luy,
                    'hinh_anh' => $advertisement->hinh_anh,
                    'trang_thai' => $advertisement->trang_thai,
                    'ma_nqc' => $advertisement->ma_nqc,
                    'ten_nqc' => $advertisement->ten_nqc,
                    'sdt' => $advertisement->so_dien_thoai
                ],
                'message' => 'Get advertisement successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request) //checked
    {
        $validated = $request->validate([
            'ten_quang_cao' => 'required|string',
            'hinh_anh' => 'nullable|url',
            'ma_nqc' => 'required|exists:nha_dang_ky_quang_cao,ma_nqc',
        ]);
        if (!$validated) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        } else {
            try {
                do {
                    $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    $ma_quang_cao = 'QC' . $uniqueNumber;
                } while (AdvertisementModel::where('ma_quang_cao', $ma_quang_cao)->exists());
                AdvertisementModel::create([
                    'ma_quang_cao' => $ma_quang_cao,
                    'ten_quang_cao' => $request->ten_quang_cao,
                    'ngay_tao' => now(),
                    'luot_phat_tich_luy' => 0,
                    'hinh_anh' => $request->hinh_anh,
                    'trang_thai' => 1,
                    'ma_nqc' => $request->ma_nqc
                ]);
                return response()->json([
                    'ma_quang_cao' => $ma_quang_cao,
                    'message' => 'Create advertisement successfully',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Create advertisement failed. Error:' . $e->getMessage(),
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function update(Request $request) //checked
    {
        $validated = $request->validate([
            'ten_quang_cao' => 'required|string'
        ]);
        if (!$validated) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
        $advertisement = AdvertisementModel::where('ma_quang_cao', $request->ma_quang_cao)->first();
        if (!$advertisement) {
            return response()->json([
                'message' => 'Advertisement not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            try {
                $advertisement->update([
                    'ten_quang_cao' => $validated['ten_quang_cao'],
                ]);
                return response()->json([
                    'message' => 'Advertisement updated successfully',
                    'status' => Response::HTTP_OK
                ], Response::HTTP_OK);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Update advertisement failed. Error:' . $e->getMessage(),
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function destroy($id) //checked
    {
        $advertisement = AdvertisementModel::where('ma_quang_cao', $id)->first();
        if (!$advertisement) {
            return response()->json([
                'message' => 'Advertisement not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            if ($advertisement->trang_thai == 1) {
                try {
                    $hasContract = AdvertisingContractModel::where('ma_quang_cao', $id)->first();
                    if ($hasContract) {
                        $advertisement->update(['trang_thai' => 0]);
                    } else {
                        $advertisement->delete();
                    }
                    return response()->json([
                        'message' => 'Advertisement deleted successfully',
                        'status' => Response::HTTP_OK
                    ], Response::HTTP_OK);
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Delete advertisement failed. Error:' . $e->getMessage(),
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return response()->json([
                    'message' => 'Advertisement cannot be deleted',
                    'status' => Response::HTTP_BAD_REQUEST
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    // hàm sử dụng quảng cáo
    //sử dụng hết quảng cáo thì phải kết thúc hợp đồng
    public function useAdvertisement($id) // checked
    {
        $advertisement = AdvertisementModel::where('ma_quang_cao', $id)->first();
        if (!$advertisement) {
            return response()->json([
                'message' => 'Advertisement not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        if ($advertisement->trang_thai != 1) {
            return response()->json([
                'message' => 'Advertisement is not active',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($advertisement->luot_phat_tich_luy > 0) {
            $advertisement->luot_phat_tich_luy -= 1;
            if ($advertisement->luot_phat_tich_luy == 0) {
                $contract = AdvertisingContractModel::where('ma_quang_cao', $id)->first();
                if ($contract) {
                    $contract->ngay_thanh_toan = now();
                    $contract->save();
                }
            }
            $advertisement->save();
            return response()->json([
                'message' => 'Advertisement used successfully',
                'remaining_plays' => $advertisement->luot_phat_tich_luy,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'No remaining plays for this advertisement',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
