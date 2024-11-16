<?php

namespace App\Http\Controllers;

use App\Models\AdvertisingContractModel;
use Illuminate\Http\Request;

class AdvertisingContractController extends Controller
{
    public function index()
    {
        $advertising_contracts = AdvertisingContractModel::all();
        return response()->json($advertising_contracts);
    }

    public function show($ma_hop_dong)
    {
        $advertising_contract = AdvertisingContractModel::where('ma_hop_dong', $ma_hop_dong)
        ->first();

        if (!$advertising_contract) {
            return response()->json(['message' => 'AdvertisingContract not found'], 404);
        }

        $relationships = $advertising_contract->relationships();
        $data = [
            'advertising_contract' => $advertising_contract,
            'advertiser' => $relationships['advertiser'],
            'advertisement' => $relationships['advertisement']
        ];
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_hop_dong' => 'required|exists:advertising_contracts,ma_hop_dong',
            'ma_quang_cao' => 'required|exists:advertising_contracts,ma_quang_cao',
            'luot_phat' => 'required|numeric|min:0',
            'doanh_thu' => 'required|numeric|min:0',
            'ngay_hieu_luc' => 'required|date',
            'ngay_hoan_thanh' => 'required|date'
        ]);

        $advertising_contract = AdvertisingContractModel::create($validated);
        return response()->json($advertising_contract, 201);
    }

    public function update(Request $request, $ma_hop_dong)
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

    public function destroy($ma_hop_dong)
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