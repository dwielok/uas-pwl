<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pengembalian.index')->only('index');
        $this->middleware('permission:pengembalian.create')->only('create', 'store');
        $this->middleware('permission:pengembalian.edit')->only('edit', 'update');
        $this->middleware('permission:pengembalian.destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $pengembalians = DB::table('pengembalian')
            ->when($request->input('title'), function ($query, $name) {
                return $query->where('peminjaman.kode', 'like', '%' . $name . '%');
            })
            ->join('peminjaman', 'peminjaman.id', '=', 'pengembalian.id_peminjaman')
            ->join('book', 'book.id', '=', 'peminjaman.id_buku')
            ->join('users', 'users.id', '=', 'peminjaman.id_user')
            ->select('pengembalian.id_user', 'peminjaman.kode', 'users.name as nama_peminjam', 'book.title as judul_buku', 'book.isbn',  DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"), DB::raw("DATE(pengembalian.tanggal_kembali) as tanggal_kembali"), 'pengembalian.status')
            ->paginate(10);
        return view('pengembalian_user.index', compact('pengembalians'));
    }
}
