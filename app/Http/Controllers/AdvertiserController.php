<?php

namespace App\Http\Controllers;

use App\Models\AdvertisertModel;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public function index()
    {
        $advertisers = AdvertiserModel::all();
        return response()->json($advertisers);
    }

    public function show($id)
    {
        $advertiser = AdvertiserModel::where('ma_nqc', $ma_nqc)
        ->first();

        if (!$advertiser) {
            return response()->json(['message' => 'Advertiser not found'], 404);
        }

        $relationships = $advertiser->relationships();
        $data = [
            'advertiser' => $advertiser,
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_nqc' => 'required|exists:advertisers,ma_nqc',
            'ten_nqc' => 'required|string|max:50',
            'so_dien_thhoai' => 'required|string|regex:/^[0-9]{10}$/'
        ]);

        $advertiser = AdvertiserModel::create($validated);
        return response()->json($advertiser, 201);
    }

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $advertiser = AdvertiserModel::where('ma_nqc', $ma_nqc)
        ->first();

        if (!$advertiser) {
            return response()->json(['message' => 'Advertiser not found'], 404);
        }

        $advertiser->delete();
        return response()->json(['message' => 'Advertiser deleted']);
    }
}