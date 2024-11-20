<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decentralization;

class DecentralizationController extends Controller
{
    public function index(){
        $decentralizations = Decentralization::all();
        return response()->json($decentralizations);
    }
    public function show($ma_phan_quyen){
        $decentralization = Decentralization::with(['chuc_nang'])->find($ma_phan_quyen);

        if ($decentralization) {
            return response()->json($decentralization);
        } else {
            return response()->json(['message' => 'Decentralization not found'], 404);
        }
    }

    // public function store(Request $request)
    // {
    //     $decentralization = Decentralization::create([
    //         'ma_phan_quyen' => $request->ma_phan_quyen,
    //         'ten_quyen_han' => $request->ten_quyen_han,
    //         'ngay_tao' => $request->ngay_tao,
    //         'tinh_trang' => $request->tinh_trang,
    //     ]);

    //     return response()->json($decentralization, 201);
    // }

    public function store(Request $request)
{
    // Retrieve the last created `ma_chuc_nang` with the prefix 'FUNC'
    $lastDecentralization = Functionn::where('ma_phan_quyen', 'like', 'AUTH%')
                              ->orderBy('ma_phan_quyen', 'desc')
                              ->first();

    // Extract the increment part or start at 1 if no previous records exist
    if ($lastDecentralization) {
        // Get the last 4 digits, convert to integer, and increment by 1
        $lastIncrement = (int) substr($lastDecentralization->ma_phan_quyen, -4);
        $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // Start from '0001' if there is no matching record
        $newIncrement = '0001';
    }

    // Generate the full `ma_chuc_nang` with the new increment
    $maChucNang = 'AUTH' . $newIncrement;

    // Create the function with the generated `ma_chuc_nang`
    $decentralization = Decentralization::create([
        'ma_phan_quyen' => $maChucNang,
        'ten_quyen_han' => $request->ten_quyen_han,
        'ngay_tao' => $request->ngay_tao,
        'tinh_trang' => 1,
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
