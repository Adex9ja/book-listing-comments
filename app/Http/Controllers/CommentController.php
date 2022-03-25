<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function getComments(Request $request): JsonResponse
    {
        $order = $request->query("order") == "ASC" ? "ASC" : "DESC";
        $comments = Comment::orderBy('id', $order)->get();

        $data = [
            "meta" => [
                "total_comment" => count($comments)
            ],
            "data" => $comments
        ];


        return response()->json(new CustomResponse("Available comment(s)", $data));
    }

    public function getCommentsByBookId(Request $request, $book_id): JsonResponse
    {

        $order = $request->query("order") == "ASC" ? "ASC" : "DESC";

        $comments = Comment::where('book_id', $book_id)->orderBy('id', $order)->get();

        $data = [
            "meta" => [
                "total_comment" => count($comments)
            ],
            "data" => $comments
        ];


        return response()->json(new CustomResponse("Available comment(s)", $data));
    }

    public function addComment(Request $request): JsonResponse
    {

        $this->validate($request, [
            'comment' => 'required | max:500',
            'book_id' => 'required',
        ]);

        try {
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->book_id = $request->book_id;
            $comment->ip_address = request()->getClientIp();


            if ($comment->save()) {
                return response()->json(new CustomResponse("Comment created successfully!", $comment));
            } else {
                return response()->json(new CustomResponse("Could not create comment", null, 500));
            }
        } catch (\Exception  $e) {
            return response()->json(new CustomResponse($e->getMessage(), null, 500 ), 500);
        }
    }
}
