<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookListUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:books_user.index')->only('index');
        $this->middleware('permission:books_user.pinjam')->only('pinjam', 'pinjam_action');
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
            ->select('book.id', 'book.isbn', 'book.title', 'book.image', 'book.author', 'book.status', 'book.read', 'users.name', DB::raw("DATE(book.publication_date) as publication_date"))
            ->paginate(10);
        return view('books_user.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pinjam(Book $book)
    {
        $user = auth()->user();
        return view('books_user.pinjam', compact('user', 'book'));
    }

    public function pinjam_action(Request $request)
    {
        $rand = rand(1231, 7879);
        $code = 'LIB' . $rand;
        Peminjaman::create([
            'kode' => $code,
            'id_user' => $request['id_user'],
            'id_buku' => $request['id_buku'],
            'tanggal_pinjam' => $request['tanggal_pinjam'],
            'tanggal_batas_kembali' => $request['tanggal_batas_kembali'],
        ]);
        Book::where('id', $request['id_buku'])->update(['status' => 0]);
        return redirect(route('peminjaman_user.index'))->with('success', 'Data Berhasil Ditambahkan');
    }
}
