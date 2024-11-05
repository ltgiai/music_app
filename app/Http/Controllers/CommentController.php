<?php

namespace App\Http\Controllers;

use App\Models\CommentModel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = CommentModel::all();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'noi_dung' => 'required|string',
            'ma_tk' => 'required|integer',
            'ma_bai_hat' => 'required|integer',
        ]);

        $validatedData['ngay_tao'] = now();

        $comment = CommentModel::create($validatedData);
        return response()->json($comment, 201);
    }

    public function show($id)
    {
        $comment = CommentModel::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json($comment);
    }

    public function update(Request $request, $id)
    {
        $comment = CommentModel::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $validatedData = $request->validate([
            'noi_dung' => 'string',
            'ma_tk' => 'integer',
            'ma_bai_hat' => 'integer',
        ]);

        $validatedData['ngay_chinh_sua'] = now(); 

        $comment->update($validatedData);
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = CommentModel::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
