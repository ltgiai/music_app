<?php

namespace App\Http\Controllers;

use App\Models\AdvertisementModel;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = AdvertisementModel::all();
        return response()->json($advertisements);
    }

    public function show($id)
    {
        $advertisement = AdvertisementModel::where('ma_quang_cao', $ma_quang_cao)
        ->first();
        
        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        $relationships = $advertisement->relationships();
        $data = [
            'advertisement' => $advertisement,
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_quang_cao' => 'required|exists:advertisements,ma_quang_cao',
            'ten_quang_cao' => 'required|string|max:50',
            'ngay_tao' => 'required|date',
            'ngay_huy' => 'required|date',
            'luot_phat' => 'nullable|numeric|min:0',
            'hinh_anh' => 'nullable|url',
            'trang_thai' => 'nullable|numeric|between:0,9'
            'ma_nqc' => 'required|exists:advertisers,ma_nqc'
        ]);

        $advertisement = AdvertisementModel::create($validated);
        return response()->json($advertisement, 201);
    }

    public function update(Request $request, $id)
    {
        $advertisement = AdvertisementModel::where('ma_quang_cao', $ma_quang_cao)
        ->first();

        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        $validated = $request->validate([
            'ma_quang_cao' => 'required|exists:advertisements,ma_quang_cao',
            'ten_quang_cao' => 'required|string|max:50',
            'ngay_tao' => 'required|date',
            'ngay_huy' => 'required|date',
            'luot_phat' => 'nullable|numeric|min:0',
            'hinh_anh' => 'nullable|url',
            'trang_thai' => 'nullable|numeric|between:0,9'
            'ma_nqc' => 'required|exists:advertisers,ma_nqc'
        ]);

        $advertisement->update($validated);
        return response()->json($advertisement);
    }

    public function destroy($id)
    {
        $advertisement = AdvertisementModel::where('ma_quang_cao', $ma_quang_cao)
        ->first();

        if (!$advertisement) {
            return response()->json(['message' => 'Advertisement not found'], 404);
        }

        $advertisement->delete();
        return response()->json(['message' => 'Advertisement deleted']);
    }
}