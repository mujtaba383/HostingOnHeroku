<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;

class BookController extends Controller
{
    public function detail($slug)
    {
        $book = Book::where('slug', $slug)->first();
        $related_books = Book::where('category_id', $book->id)->get();
        return view('book.detail', compact('book', 'related_books')); 
    }
}
