<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Functionn;

class FunctionnController extends Controller
{
    public function index(){
        $functionns = Functionn::all();
        return response()->json($functionns);
    }
    public function show($ma_chuc_nang){
        $functionn = Functionn::find($ma_chuc_nang);

        if ($functionn) {
            return response()->json($decentfunctionnralization);
        } else {
            return response()->json(['message' => 'Functionn not found'], 404);
        }
    }
    public function store(Request $request){
        // Retrieve the last created `ma_chuc_nang` with the prefix 'FUNC'
        $lastFunctionn = Functionn::where('ma_chuc_nang', 'like', 'FUNC%')
                                ->orderBy('ma_chuc_nang', 'desc')
                                ->first();

        // Extract the increment part or start at 1 if no previous records exist
        if ($lastFunctionn) {
            // Get the last 4 digits, convert to integer, and increment by 1
            $lastIncrement = (int) substr($lastFunctionn->ma_chuc_nang, -4);
            $newIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start from '0001' if there is no matching record
            $newIncrement = '0001';
        }

        // Generate the full `ma_chuc_nang` with the new increment
        $maChucNang = 'FUNC' . $newIncrement;

        // Create the function with the generated `ma_chuc_nang`
        $functionn = Functionn::create([
            'ma_chuc_nang' => $maChucNang,
            'ten_chuc_nang' => $request->ten_chuc_nang,
        ]);

        return response()->json($functionn, 201);

        {
            $functionn = Functionn::create([
                'ma_chuc_nang' => $request->ma_chuc_nang,
                'ten_chuc_nang' => $request->ten_chuc_nang,
            ]);

            return response()->json($functionn, 201);
        }
    }

    public function update(Request $request, $ma_chuc_nang){
        $functionn = Functionn::find($ma_chuc_nang);

        if ($functionn) {
            $functionn->update([
            'ten_chuc_nang' => $request->ten_chuc_nang,
            ]);
            return response()->json($functionn);
        } else {
            return response()->json(['message' => 'Functionn not found'], 404);
        }
    }
    
    public function destroy($ma_chuc_nang){
        $functionn = Functionn::find($ma_chuc_nang);

        if ($functionn) {
            $functionn->delete();
            return response()->json(['message' => 'Functionn deleted successfully']);
        } else {
            return response()->json(['message' => 'Functionn not found'], 404);
        }
    }
}
