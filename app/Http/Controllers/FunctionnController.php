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
    public function store(Request $request)
    {
        $functionn = Functionn::create([
            'ma_chuc_nang' => $request->ma_chuc_nang,
            'ten_chuc_nang' => $request->ten_chuc_nang,
        ]);

        return response()->json($functionn, 201);
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
