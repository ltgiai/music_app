<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decentralization;

class DecentralizationController extends Controller
{
    public function index(){
        $decentralizations = Decentralization::with('taikhoan')->get();
        return response()->json($decentralizations);
    }
    public function show($ma_phan_quyen){
        $decentralization = Decentralization::with(['chuc_nang', 'taikhoan'])->find($ma_phan_quyen);

        if ($decentralization) {
            return response()->json($decentralization);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $decentralization = Decentralization::create([
            'ma_phan_quyen' => $request->ma_phan_quyen,
            'ten_quyen_han' => $request->ten_quyen_han,
            'ngay_tao' => $request->ngay_tao,
            'tinh_trang' => $request->tinh_trang,
        ]);

        return response()->json($decentralization, 201);
    }

    public function update(Request $request, $ma_phan_quyen){
        $decentralization = Decentralization::find($ma_phan_quyen);

        if ($decentralization) {
            $decentralization->update([
            'ten_quyen_han' => $request->ten_quyen_han,
            'tinh_trang' => $request->tinh_trang,
            ]);
            return response()->json($decentralization);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }
    public function destroy($ma_phan_quyen){
        $decentralization = Decentralization::find($ma_phan_quyen);

        if ($decentralization) {
            $decentralization->delete();
            return response()->json(['message' => 'Decentralization deleted successfully']);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }

    public function attachFunctionn($ma_phan_quyen, $ma_chuc_nang)
    {
        $decentralization = Decentralization::find($ma_phan_quyen);
        if ($decentralization) {
            $decentralization->chucNangs()->attach($ma_chuc_nang);
            return response()->json(['message' => 'Functionn attached to quyen']);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }
}
