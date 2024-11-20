<?php

namespace App\Http\Controllers;

use App\Models\AdvertiserModel;
use App\Models\AdvertisementModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdvertiserController extends Controller
{
    public function index() //checked
    {
        $advertisers = AdvertiserModel::all();
        if (!$advertisers) {
            return response()->json([
                'message' => 'No advertiser found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "advertisers" => $advertisers->map(function ($ad) {
                    return [
                        'ma_nqc' => $ad->ma_nqc,
                        'ten_nqc' => $ad->ten_nqc,
                        'so_dien_thoai' => $ad->so_dien_thoai,
                        'trang_thai' => $ad->trang_thai 
                    ];
                }),
                'message' => 'Get all advertiser successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function show($ma_nqc) //checked
    {
        $advertiser = AdvertiserModel::where('ma_nqc', $ma_nqc)
            ->first();
        if (!$advertiser) {
            return response()->json([
                'message' => 'No advertiser found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "data" => [
                    'ma_nqc' => $advertiser->ma_nqc,
                    'ten_nqc' => $advertiser->ten_nqc,
                    'so_dien_thoai' => $advertiser->so_dien_thoai
                ],
                'message' => 'Get all advertiser successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request) //checked
    {
        $validated = $request->validate([
            'ten_nqc' => 'required|string|max:50',
            'so_dien_thoai' => 'required|string|regex:/^[0-9]{10}$/'
        ]);
        if (!$validated) {
            return response()->json([
                'message' => 'Invalid input',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
        try {
            do {
                $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $ma_nqc = 'NQC' . $uniqueNumber;
            } while (AdvertiserModel::where('ma_nqc', $ma_nqc)->exists());
            AdvertiserModel::create([
                'ma_nqc' => $ma_nqc,
                'ten_nqc' => $request->ten_nqc,
                'so_dien_thoai' => $request->so_dien_thoai,
                'trang_thai' => 1   
            ]);
            return response()->json([
                'ma_nqc' =>  $ma_nqc,
                'message' => 'Advertiser created',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal server error',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $ma_nqc) // ko thể sửa 
    {
        $advertiser = AdvertiserModel::where('ma_nqc', $ma_nqc)
            ->first();

        if (!$advertiser) {
            return response()->json(['message' => 'Advertiser not found'], 404);
        }

        $validated = $request->validate([
            'ma_nqc' => 'required|exists:advertisers,ma_nqc',
            'ten_nqc' => 'required|string|max:50',
            'so_dien_thoai' => 'required|string|regex:/^[0-9]{10}$/'

        ]);

        $advertiser->update($validated);
        return response()->json($advertiser);
    }

    // chỉ có thể xóa nếu như mà nhà quảng cáo không có quảng cáo nào
    public function destroy($ma_nqc) // checked
    {
        $advertiser = AdvertiserModel::where('ma_nqc', $ma_nqc)->first();
        if (!$advertiser) {
            return response()->json([
                'message' => 'Advertiser not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
        $hasAds = AdvertisementModel::where('ma_nqc', $ma_nqc)->exists();
        if ($hasAds) {
            return response()->json([
                'message' => 'Cannot delete advertiser with associated ads',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
        $advertiser->update(['trang_thai' => 0]);
        return response()->json([ 
            'message' => 'Advertiser deleted', 
            'status' => Response::HTTP_OK 
        ], Response::HTTP_OK);
    }
}
