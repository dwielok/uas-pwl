<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pengembalian_user.index')->only('index');
        $this->middleware('permission:pengembalian_user.create')->only('create', 'store');
        $this->middleware('permission:pengembalian_user.edit')->only('edit', 'update');
        $this->middleware('permission:pengembalian_user.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $pengembalians = DB::table('pengembalian')
            ->when($request->input('title'), function ($query, $name) {
                return $query->where('peminjaman.kode', 'like', '%' . $name . '%');
            })
            ->join('peminjaman', 'peminjaman.id', '=', 'pengembalian.id_peminjaman')
            ->join('book', 'book.id', '=', 'peminjaman.id_buku')
            ->join('users', 'users.id', '=', 'peminjaman.id_user')
            ->select('pengembalian.id', 'peminjaman.kode', 'users.name as nama_peminjam', 'book.title as judul_buku', 'book.isbn',  DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"), DB::raw("DATE(pengembalian.tanggal_kembali) as tanggal_kembali"), 'pengembalian.status')
            ->where('peminjaman.id_user', '=', auth()->user()->id)
            ->paginate(10);
        return view('pengembalian_user.index', compact('pengembalians'));
    }
    
}
