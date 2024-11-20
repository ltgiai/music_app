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
        $latestComment = CommentModel::orderBy('ma_binh_luan', 'desc')->first();
        if ($latestComment) {
            $lastIdNumber = (int) substr($latestComment->ma_binh_luan, 2);
            $newIdNumber = $lastIdNumber + 1;
            $newId = 'BL' . str_pad($newIdNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $newId = 'BL0001';
        }
        $validatedData = $request->validate([
            'noi_dung' => 'required|string',
            'ma_tk' => 'required|string',
            'ma_bai_hat' => 'required|string',
        ]);

        $validatedData['ma_binh_luan'] = $newId;

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
            'ma_tk' => 'string',
            'ma_bai_hat' => 'string',
        ]);

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
    public function getCommentsBySong($songId)
    {
        // Lấy tất cả bình luận của bài hát theo ma_bai_hat
        $comments = CommentModel::where('ma_bai_hat', $songId)->get();
        
        if ($comments->isEmpty()) {
            return response()->json(['message' => 'No comments found for this song'], 404);
        }

        return response()->json($comments);
    }
}
