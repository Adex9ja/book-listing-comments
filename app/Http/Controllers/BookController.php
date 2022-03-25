<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\Comment;
use App\Models\CustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController
{
    private Repository $api;

    public function __construct()
    {
        $this->api = new Repository();
    }

    public function getBooks(Request $request): JsonResponse
    {
        try {
            if ($request->query("name")) {
                $book_name = $request->query("name");
                $books = $this->api->getBookByName($book_name);
            } else if ($request->query("fromReleaseDate")) {
                $fromReleaseDate = $request->query("fromReleaseDate");
                $books = $this->api->getBookByFromReleaseDate($fromReleaseDate);
            } else {
                $books = $this->api->getAllBooks();
            }

            if ($request->query("order") == 'DESC') {
                $books = array_reverse($books);
            }

            $data = array_map(function ($book){ return $this->mapBookData($book); }, $books);

            return response()->json(new CustomResponse("Books available", $data ));
        } catch (\Exception  $e) {
            return response()->json(new CustomResponse($e->getMessage(), null, 500 ), 500);
        }
    }

    public function getBookById($id): JsonResponse
    {
        try {
            $response = $this->api->getBookByID($id);
            $book = $this->mapBookData($response);
            return  response()->json(new CustomResponse("Book available", $book));
        } catch (\Exception  $e) {
            return response()->json(new CustomResponse($e->getMessage(), null, 500 ), 500);
        }
    }

    public function mapBookData($book): array
    {

        // get book id from book url
        if (preg_match("/\/(\d+)$/", $book->url, $recordMatch)) {
            $book_id = $recordMatch[1];
        }

        // SET NEW OBJECT ARRAY FROM TRANSFORMATION
        $data["url"] = $book->url;
        $data["book_id"] = $book_id;
        $data["name"] = $book->name;

        $data["number_of_authors"] = count($book->authors);
        $data["authors"] = $book->authors;

        $data["number_of_characters"] = count($book->characters);
        $data["characters"] = $book->characters;

        $comment = Comment::where('book_id', $book_id)->orderByDesc("id")->get();

        $data["number_of_comments"] = count($comment);
        $data["comment"] = $comment;

        return $data;
    }
}
