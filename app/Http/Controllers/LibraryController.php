<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Library;
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

    /**
     * Lists available books.
     *
     * @return JsonResponse
     */
    function listAvailableBooks(): JsonResponse {
        return response()->json(['status' => 'success', 'data' => Book::where('status', '=', 'available')->get()], 200);
    }

    /**
     * Lists borrowed books.
     *
     * @return JsonResponse
     */
    function listBorrowedBooks(): JsonResponse {
        return response()->json(['status' => 'success', 'data' => Book::where('status', '=', 'borrowed')->get()], 200);
    }

    /**
     * Request should have a memberName and a isbn.
     * Server lets the member borrow a book if it isn't borrowed jet and returns a response to the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    function borrowBook(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'memberName' => 'required|string|exists:members,name',
            'isbn' => 'required|string|max:13|min:13|exists:books,isbn',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'error', 'error' => $validator->errors()], 400);
        }

        $book = Book::where('isbn', '=', $request->input('isbn'))->first();

        if ($book->status !== 'available') {
            return response()->json(['status' => 'error', 'error' => 'Book is not available'], 400);
        }

        $book->status = 'borrowed';
        $book->save();

        $libraryItem = new Library();
        $libraryItem->book_id = $book->id;
        $libraryItem->member_id = Member::where('name', '=', strtolower($request->input('memberName')))->first()->id;
        $libraryItem->save();


        return  response()->json(['status' => 'success', 'message' => 'You have borrowed the book.', 'data' => $book], 200);
    }

    /**
     * Request should have a memberName and a isbn.
     * The user should always be able to return the book even if the database says it's not borrowed.
     * Server returns a response to the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    function returnBook(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'memberName' => 'required|string|exists:members,name',
            'isbn' => 'required|string|max:13|min:13|exists:books,isbn',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => 'error', 'error' => $validator->errors()], 400);
        }

        $book = Book::where('isbn', '=', $request->input('isbn'))->first();
        $book->status = 'available';
        $book->save();

        $member = Member::where('name', '=', strtolower($request->input('memberName')))->first();
        Library::where('book_id', '=', $book->id)->where('member_id', '=', $member->id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Book has been returned.', 'data' => $book], 200);
    }
}
