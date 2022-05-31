<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:book.index')->only('index');
        $this->middleware('permission:book.create')->only('create', 'store');
        $this->middleware('permission:book.edit')->only('edit', 'update');
        $this->middleware('permission:book.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = DB::table('book')
            ->when($request->input('title'), function ($query, $name) {
                return $query->where('book.title', 'like', '%' . $name . '%');
            })
            ->join('users', 'users.id', '=', 'book.user_id')
            ->select('book.id', 'book.isbn', 'book.title', 'book.author', 'book.read', 'users.name', DB::raw("DATE(book.publication_date) as publication_date"))
            ->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::all();
        return view('books.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        //

        $fileimage = $request->file('image');
        $nameimage = time() . '.' . $fileimage->getClientOriginalExtension();
        $fileimage->move(public_path('images'), $nameimage);

        $fullPathUriImage = '/images/' . $nameimage;

        $filepdf = $request->file('file');
        $namepdf = time() . '.' . $filepdf->getClientOriginalExtension();
        $filepdf->move(public_path('file'), $namepdf);

        $fullPathUriPdf = '/file/' . $namepdf;
        Book::create([
            'isbn' => $request->isbn,
            'title' => $request->title,
            'image' => $fullPathUriImage,
            'author' => $request->author,
            'publication_date' => $request->publication_date,
            'file' => $fullPathUriPdf,
            'read' => 0,
            'user_id' => $request->user_id,
        ]);
        return redirect(route('book.index'))->with('success', 'Data Berhasil Ditambahkan');
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
    public function edit(Book $book)
    {
        //
        $users = User::all();
        return view('books.edit', compact('book', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
        $request->validated();
        $input = $request->all();
        if ($image = $request->file('image')) {
            $fileimage = $request->file('image');
            $nameimage = time() . '.' . $fileimage->getClientOriginalExtension();
            $fileimage->move(public_path('images'), $nameimage);

            $fullPathUriImage = '/images/' . $nameimage;
            $input['image'] = "$fullPathUriImage";
        } else {
            unset($input['image']);
        }

        if ($file = $request->file('file')) {
            $filepdf = $request->file('file');
            $namepdf = time() . '.' . $filepdf->getClientOriginalExtension();
            $filepdf->move(public_path('file'), $namepdf);

            $fullPathUriPdf = '/file/' . $namepdf;
            $input['file'] = "$fullPathUriPdf";
        } else {
            unset($input['file']);
        }

        $book->update($input);

        return redirect()->route('book.index')->with('success', 'Book Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
        $book->delete();
        return redirect()->route('book.index')->with('success', 'Book Deleted Successfully');
    }
}
