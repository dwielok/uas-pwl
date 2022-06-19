<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Faker\Provider\File;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:laporan.peminjaman_per_user')->only('peminjaman_per_user', 'peminjaman_per_user_pdf');
        $this->middleware('permission:laporan.pengembalian_per_user')->only('pengembalian_per_user', 'pengembalian_per_user_pdf');
        $this->middleware('permission:laporan.denda_per_user')->only('denda_per_user', 'denda_per_user_pdf');
    }

    public function peminjaman_per_user(Request $request)
    {
        $users = DB::table('users')
            ->when($request->input('users.name'), function ($query, $name) {
                return $query->where('users.name', 'like', '%' . $name . '%');
            })
            ->select('users.id', 'users.name', 'users.email', DB::raw('ifnull(COUNT(peminjaman.id),0) as jumlah_peminjaman'))
            ->join('peminjaman', 'peminjaman.id_user', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->paginate(10);
        return view('laporan.peminjaman_per_user', compact('users'));
    }

    public function peminjaman_per_user_pdf(User $user)
    {
        $peminjamans = Peminjaman::where('id_user', $user->id)
            ->join('book', 'peminjaman.id_buku', '=', 'book.id')
            ->select('peminjaman.id', 'peminjaman.kode', 'book.title as judul_buku', 'book.isbn', DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"))
            ->get();
        $pdf = PDF::loadView('laporan.peminjaman_per_user_pdf', compact('peminjamans', 'user'));
        return $pdf->stream('Laporan ' . $user->name . '.pdf');
    }

    public function pengembalian_per_user(Request $request)
    {
        $users = DB::table('users')
            ->when($request->input('users.name'), function ($query, $name) {
                return $query->where('users.name', 'like', '%' . $name . '%');
            })
            ->select('users.id', 'users.name', 'users.email', DB::raw('ifnull(COUNT(pengembalian.id),0) as jumlah_pengembalian'))
            ->join('peminjaman', 'peminjaman.id_user', '=', 'users.id')
            ->join('pengembalian', 'pengembalian.id_peminjaman', '=', 'peminjaman.id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->paginate(10);
        return view('laporan.pengembalian_per_user', compact('users'));
    }

    public function pengembalian_per_user_pdf(User $user)
    {
        $pengembalians = Peminjaman::where('id_user', $user->id)
            ->join('book', 'peminjaman.id_buku', '=', 'book.id')
            ->join('pengembalian', 'pengembalian.id_peminjaman', '=', 'peminjaman.id')
            ->select('peminjaman.id', 'peminjaman.kode', 'book.title as judul_buku', 'book.isbn', DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"), DB::raw("DATE(pengembalian.tanggal_kembali) as tanggal_kembali"), 'pengembalian.status')
            ->get();
        $pdf = PDF::loadView('laporan.pengembalian_per_user_pdf', compact('pengembalians', 'user'));
        return $pdf->stream('Laporan ' . $user->name . '.pdf');
    }

    public function denda_per_user(Request $request)
    {
        $users = DB::table('users')
            ->when($request->input('users.name'), function ($query, $name) {
                return $query->where('users.name', 'like', '%' . $name . '%');
            })
            ->select('users.id', 'users.name', 'users.email', DB::raw('ifnull(COUNT(denda.id),0) as jumlah_denda'))
            ->join('peminjaman', 'peminjaman.id_user', '=', 'users.id')
            ->join('pengembalian', 'pengembalian.id_peminjaman', '=', 'peminjaman.id')
            ->join('denda', 'denda.id_pengembalian', '=', 'pengembalian.id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->paginate(10);
        return view('laporan.denda_per_user', compact('users'));
    }

    public function denda_per_user_pdf(User $user)
    {
        $dendas = Pengembalian::with(['denda' => function ($query) {
            $query->select('denda', 'id_pengembalian', 'status');
        }])->join('peminjaman', 'peminjaman.id', '=', 'pengembalian.id_peminjaman')
            ->join('users', 'users.id', '=', 'peminjaman.id_user')
            ->join('book', 'book.id', '=', 'peminjaman.id_buku')
            ->join('denda', 'denda.id_pengembalian', '=', 'pengembalian.id')
            ->select('pengembalian.id', 'peminjaman.kode', 'book.title as judul_buku', 'book.isbn', DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"), DB::raw("DATE(pengembalian.tanggal_kembali) as tanggal_kembali"))
            ->where('peminjaman.id_user', $user->id)
            ->get();
        $pdf = PDF::loadView('laporan.denda_per_user_pdf', compact('dendas', 'user'));
        return $pdf->stream('Laporan ' . $user->name . '.pdf');
    }
}
