<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;


Route::middleware('auth:sactum')->group(function () {
    Route::apiResource('books', BookController::class);
});

class BookController extends Controller
{
    public function index() {
        return Book::all();
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required',
            'published_year' => 'required|integer',
            'genre' => 'required',
        ]);

        return Book::create($validated);
    }

    public function show(Book $book) {
        return $book;
    }

    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required',
            'published_year' => 'required|integer',
            'genre' => 'required',
        ]);

        $book->update($validated);
        return $book;
    }

    public function destroy(Book $book) {
        $book->delete();
        return response()->noContent();
    }
}
