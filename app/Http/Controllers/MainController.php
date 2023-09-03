<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Team;
use App\Media;
use App\Author;
use App\Book;
use App\Category;



class MainController extends Controller
{
    public function index()
    {
        $sliders = Media::where(['status' => 'ACTIVE', 'media_type' => 'slider'])->latest()->limit(2)->get();
        $upcoming_books = Book::where('status', 'UPCOMING')->limit(5)->get();
        $downloaded_books = Book::with('category', 'author')->orderBy('downloaded', 'DESC')->limit(4)->get();
        $recommended_books = Book::where('recommended', 'yes')->limit(5)->get();
        $categories = Category::where('status', 'ACTIVE')->get();
        $book_collections = Book::with('author')->where('status', 'ACTIVE')->paginate(10);
        $author_feature = Author::with('author_books')->where(['status' => 'ACTIVE', 'author_feature' => 'yes'])->inRandomOrder()->first();
        $galleries = Media::where(['status' => 'ACTIVE', 'media_type' => 'gallery'])->limit(6)->get();
        return view('index', compact('sliders', 'upcoming_books', 'downloaded_books', 'recommended_books', 'categories', 'book_collections', 'author_feature', 'galleries'));
    }

    public function about()
    {
        $teams = Team::where('status', 'ACTIVE')->get();
        return view('about', compact('teams'));
    }

    public function gallery()
    {
        $galleries = Media::where(['status' => 'ACTIVE', 'media_type' => 'gallery'])->paginate(8);
        return view('gallery', compact('galleries'));
    }

    public function author()
    {
        $getSearch = request()->get('letter');
        $authors = Author::where([['title', 'LIKE', "$getSearch%"],['status', '=', 'ACTIVE']])->latest()->paginate(4);
        $authorFeatures = Author::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->limit(4)->get();
        $downloaded_books = Book::orderBy('downloaded', 'DESC')->limit(4)->get();
        return view('author', compact('authors', 'downloaded_books', 'authorFeatures'));
    }

    public function author_detail($slug)
    {
        $author = Author::where('slug', $slug)->first();
        return view('author_detail', compact('author'));
    }

    public function contact()
    {
        return view('contact');
    }
}
