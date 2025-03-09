<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    /**
     * Adds a book to the database and returns a response to the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    function addBook(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:books,title',
            'author' => 'required|string',
            'isbn' => 'required|string|max:13|min:13|unique:books,isbn',
            'status' => 'required|in:available,borrowed',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'error', 'error' => $validator->errors()], 400);
        }

        $book = new Book();
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->isbn = $request->input('isbn');
        $book->status = $request->input('status');
        $book->save();

        return response()->json(['status' => 'success', 'data' => $book], 201);
    }

    /**
     * Add a member to the database and returns a response to the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    function addMember(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:members,name',
            'email' => 'required|string|email|unique:members,email',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'error', 'error' => $validator->errors()], 400);
        }

        $member = new Member();
        $member->name = strtolower($request->input('name'));
        $member->email = strtolower($request->input('email'));
        $member->save();

        return response()->json(['status' => 'success', 'data' => $member], 201);
    }

    function listAvailableBooks(): JsonResponse {
        return response()->json(['status' => 'success', 'data' => Book::where('status', '=', 'available')->get()], 200);
    }
}
