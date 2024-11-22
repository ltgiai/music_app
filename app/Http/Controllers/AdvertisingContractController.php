<?php

namespace App\Http\Controllers;

use App\Models\AdvertisingContractModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdvertisingContractController extends Controller
{
    public function index() // checked
    {
        $advertising_contracts = DB::table('hop_dong_quang_cao')
            ->join('quang_cao', 'hop_dong_quang_cao.ma_quang_cao', '=', 'quang_cao.ma_quang_cao')
            ->select('hop_dong_quang_cao.*', 'quang_cao.ten_quang_cao', 'quang_cao.trang_thai', 'quang_cao.hinh_anh')
            ->get();
        if ($advertising_contracts->isEmpty()) {
            return response()->json([
                'message' => 'No advertising contract found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "advertising_contracts" => $advertising_contracts->map(function ($contract) {
                    return [
                        'ma_hop_dong' => $contract->ma_hop_dong,
                        'ma_quang_cao' => $contract->ma_quang_cao,
                        'ten_quang_cao' => $contract->ten_quang_cao,
                        'hinh_anh' => $contract->hinh_anh,
                        'luot_phat' => $contract->luot_phat,
                        'doanh_thu' => $contract->doanh_thu,
                        'ngay_hieu_luc' => $contract->ngay_hieu_luc,
                        'ngay_hoan_thanh' => $contract->ngay_hoan_thanh,
                        'trang_thai_quang_cao' => $contract->trang_thai,
                    ];
                }),
                'message' => 'Get all advertising contracts successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function show($ma_hop_dong) //checked
    {
        $advertising_contract = DB::table('hop_dong_quang_cao')
            ->join('quang_cao', 'hop_dong_quang_cao.ma_quang_cao', '=', 'quang_cao.ma_quang_cao')
            ->select('hop_dong_quang_cao.*', 'quang_cao.ten_quang_cao', 'quang_cao.trang_thai', 'quang_cao.hinh_anh')
            ->where('hop_dong_quang_cao.ma_hop_dong', '=', $ma_hop_dong)
            ->first();
        if (!$advertising_contract) {
            return response()->json([
                'message' => 'No advertising contract found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json([
                "data" => [
                    'ma_hop_dong' => $advertising_contract->ma_hop_dong,
                    'ma_quang_cao' => $advertising_contract->ma_quang_cao,
                    'ten_quang_cao' => $advertising_contract->ten_quang_cao,
                    'hinh_anh' => $advertising_contract->hinh_anh,
                    'luot_phat' => $advertising_contract->luot_phat,
                    'doanh_thu' => $advertising_contract->doanh_thu,
                    'ngay_hieu_luc' => $advertising_contract->ngay_hieu_luc,
                    'ngay_hoan_thanh' => $advertising_contract->ngay_hoan_thanh,
                    'trang_thai_quang_cao' => $advertising_contract->trang_thai,
                ],
                'message' => 'Get all advertising contracts successfully',
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_quang_cao' => 'required|exists:quang_cao,ma_quang_cao',
            'luot_phat' => 'required|numeric|min:0',
            'doanh_thu' => 'required|numeric|min:0',
            'ngay_hieu_luc' => 'required|date',
            'ngay_hoan_thanh' => 'required|date'    
        ]);
        if (!$validated) {
            return response()->json(
                [
                    'message' => 'Invalid data',
                    'status' => Response::HTTP_BAD_REQUEST
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            try {
                do {
                    $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    $ma_hop_dong = 'HD' . $uniqueNumber;
                } while (AdvertisingContractModel::where('ma_hop_dong', $ma_hop_dong)->exists());
                AdvertisingContractModel::create([
                    'ma_hop_dong' => $ma_hop_dong,
                    'ma_quang_cao' => $validated['ma_quang_cao'],
                    'luot_phat' => $validated['luot_phat'],
                    'doanh_thu' => $validated['doanh_thu'],
                    'ngay_hieu_luc' => $validated['ngay_hieu_luc'], 
                    'ngay_hoan_thanh' => $validated['ngay_hoan_thanh'],
                ]);
                DB::table('quang_cao')
                    ->where('ma_quang_cao', $validated['ma_quang_cao'])
                    ->update(['luot_phat_tich_luy' => $validated['luot_phat']]);
                return response()->json(
                    [
                        'message' => 'Create advertising contract successfully',
                        'status' => Response::HTTP_CREATED
                    ],
                    Response::HTTP_CREATED
                );
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'message' => 'Error while generating advertisement code',
                        'error' => $e->getMessage(),
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }
    }

    public function update(Request $request, $ma_hop_dong) // ko có chỉnh sửa hợp đồng
    {
        $advertising_contract = AdvertisingContractModel::where('ma_hop_dong', $ma_hop_dong)
            ->first();

        if (!$advertising_contract) {
            return response()->json(['message' => 'AdvertisingContract not found'], 404);
        }

        $validated = $request->validate([
            'ma_hop_dong' => 'required|exists:advertising_contracts,ma_hop_dong',
            'ma_quang_cao' => 'required|exists:advertising_contracts,ma_quang_cao',
            'luot_phat' => 'required|numeric|min:0',
            'doanh_thu' => 'required|numeric|min:0',
            'ngay_hieu_luc' => 'required|date',
            'ngay_hoan_thanh' => 'required|date'
        ]);

        $advertising_contract->update($validated);
        return response()->json($advertising_contract);
    }

    public function destroy($ma_hop_dong) // ko thấy nút xóa hợp đồng
    {
        $advertising_contract = AdvertisingContractModel::where('ma_hop_dong', $ma_hop_dong)
            ->first();

        if (!$advertising_contract) {
            return response()->json(['message' => 'AdvertisingContract not found'], 404);
        }

        $advertising_contract->delete();
        return response()->json(['message' => 'AdvertisingContract deleted']);
    }
}
