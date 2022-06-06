<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:peminjaman.index')->only('index');
        $this->middleware('permission:peminjaman.create')->only('create', 'store');
        $this->middleware('permission:peminjaman.edit')->only('edit', 'update');
        $this->middleware('permission:peminjaman.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $peminjamans = DB::table('peminjaman')
            ->when($request->input('title'), function ($query, $name) {
                return $query->where('peminjaman.kode', 'like', '%' . $name . '%');
            })
            ->join('book', 'book.id', '=', 'peminjaman.id_buku')
            ->join('users', 'users.id', '=', 'peminjaman.id_user')
            ->select('peminjaman.id', 'peminjaman.kode', 'users.name as nama_peminjam', 'book.title as judul_buku', 'book.isbn', DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"))
            ->paginate(10);
        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $books = Book::all();
        return view('peminjaman.create', compact('users', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeminjamanRequest $request)
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
        return redirect(route('peminjaman.index'))->with('success', 'Data Berhasil Ditambahkan');;
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
    public function edit(Peminjaman $peminjaman)
    {
        $users = User::all();
        $books = Book::all();
        return view('peminjaman.edit', compact('books', 'users', 'peminjaman'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeminjamanRequest $request, Peminjaman $peminjaman)
    {
        $validate = $request->validated();

        $peminjaman->update($validate);
        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman Deleted Successfully');
    }
}
