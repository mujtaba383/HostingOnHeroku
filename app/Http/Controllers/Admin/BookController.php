<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;
use App\Author;
use App\Category;
use App\Country;
use File;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchTerm = request()->get('s');
        $books = Book::with('author', 'category')->orWhere('title', 'LIKE', "%$searchTerm%")->latest()->paginate(15);
        return view('admin.book.index')
            ->with(compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::where('status', 'ACTIVE')->get();
        $categories = Category::where('status', 'ACTIVE')->get();
        $countries = Country::get();
        return view('admin.book.create')
            ->with(compact('authors', 'categories', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
            'slug' => 'required',
            'category_id' => 'required|not_in:0',
            'author_id' => 'required|not_in:0',
            'availability' => 'required',
            'price' => 'required',
            'country_of_publisher' => 'required|not_in:none',
        ]);

        $fileName = null;
        if (request()->hasFile('book_img')) {
            $file = request()->file('book_img');
            $fileName = md5($file->getClientOriginalName()) . time() . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/', $fileName);
        }

       $fileUpload = null;
        if (request()->hasFile('book_upload')) {
            $file = request()->file('book_upload');
            $fileUpload = md5($file->getClientOriginalName()) . time() . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/', $fileUpload);
        }

        Book::create([
            'category_id' => request()->get('category_id'),
            'author_id' => request()->get('author_id'),
            'title' => request()->get('title'),
            'slug' => request()->get('slug'),
            'availability' => request()->get('availability'),
            'price' => request()->get('price'),
            'rating' => 'rating',
            'publisher' => request()->get('publisher'),
            'country_of_publisher' => request()->get('country_of_publisher'),
            'isbn' => request()->get('isbn'),
            'isbn-10' => request()->get('isbn-10'),
            'audience' => request()->get('audience'),
            'format' => request()->get('format'),
            'language' => request()->get('language'),
            'description' => request()->get('description'),
            'book_upload' => $fileUpload,
            'book_img' => $fileName,
            'total_pages' => request()->get('total_pages'),
            'downloaded' => request()->get('downloaded'),
            'edition_number' => request()->get('edition_number'),
            'recommended' => request()->get('recommended'),
            'status' => 'DEACTIVE',
        ]);

        return  redirect()->to('/admin/book');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        $authors = Author::where('status', 'ACTIVE')->get();
        $categories = Category::where('status', 'ACTIVE')->get();
        $countries = Country::get();
        return view('admin.book.edit')
            ->with(compact('book', 'authors', 'categories', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        $currentImage = $book->book_img;
        $fileName = null;
        if (request()->hasFile('book_img')) {
            $file = request()->file('book_img');
            $fileName = md5($file->getClientOriginalName()) . time() . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/', $fileName);
        }

        $currentUpload = $book->book_upload;
        $fileUpload = null;
        if (request()->hasFile('book_upload')) {
            $file = request()->file('book_upload');
            $fileUpload = md5($file->getClientOriginalName()) . time() . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/', $fileUpload);
        }

        $book->update([
           'category_id' => request()->get('category_id'),
            'author_id' => request()->get('author_id'),
            'title' => request()->get('title'),
            'slug' => request()->get('slug'),
            'availability' => request()->get('availability'),
            'price' => request()->get('price'),
            'rating' => 'rating',
            'publisher' => request()->get('publisher'),
            'country_of_publisher' => request()->get('country_of_publisher'),
            'isbn' => request()->get('isbn'),
            'isbn-10' => request()->get('isbn-10'),
            'audience' => request()->get('audience'),
            'format' => request()->get('format'),
            'language' => request()->get('language'),
            'description' => request()->get('description'),
            'book_upload' => ($fileUpload) ? $fileUpload : $currentUpload,
            'book_img' => ($fileName) ? $fileName : $currentImage,
            'total_pages' => request()->get('total_pages'),
            'downloaded' => request()->get('downloaded'),
            'edition_number' => request()->get('edition_number'),
            'recommended' => request()->get('recommended'),
        ]);

        if ($fileName) {
            File::delete('./uploads/' . $currentImage);
        }

        if ($fileUpload) {
            File::delete('./uploads/' . $currentUpload);
        }

        return redirect()->to('/admin/book');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) 
        {
            $book = Book::find($id);
            $currentImage = $book->book_img;
            $currentUpload = $book->book_upload;
            $book->delete();
            File::delete('./uploads/' . $currentImage);
            File::delete('./uploads/' . $currentUpload);
            return 'true';
        }
        return redirect()->back();
    }

    public function status(Request $request, $id)
    {
        if ($request->ajax()) 
        {
            $book = Book::find($id);
            $newStatus = ($book->status == 'DEACTIVE') ? 'ACTIVE' : 'DEACTIVE';
            $book->update([
                'status' => $newStatus
            ]);
        return $newStatus;    
        }
        return redirect()->back();
    }

    public function status_active(Request $request)
    {
        if ($request->ajax()) 
        {
            foreach ($request->statusAll as $key => $id) {
                Book::where('id', $id)->update(['status' => 'ACTIVE']);
            }
            $record = Book::find($request->statusAll);
            return $record;
        }
        return false;
    }

    public function status_deactive(Request $request)
    {
        if ($request->ajax()) 
        {
            foreach ($request->statusAll as $key => $id) {
                Book::where('id', $id)->update(['status' => 'DEACTIVE']);
            }
            $record = Book::find($request->statusAll);
            return $record;
        }
        return false;
    }

    public function delete_all(Request $request)
    {
        if ($request->ajax()) 
        {
            foreach ($request->statusAll as $key => $id) {
                Book::where('id', $id)->delete();
            }
            $record = Book::find($request->statusAll);
            return $record;
        }
        return false;
    }
}
