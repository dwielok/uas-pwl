<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:denda.index')->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dendas = DB::table('denda')
            ->when($request->input('title'), function ($query, $name) {
                return $query->where('peminjaman.kode', 'like', '%' . $name . '%');
            })
            ->join('peminjaman', 'peminjaman.id', '=', 'denda.id_peminjaman')
            ->join('book', 'book.id', '=', 'peminjaman.id_buku')
            ->join('users', 'users.id', '=', 'peminjaman.id_user')
            ->join('pengembalian', 'pengembalian.id', '=', 'denda.id_pengembalian')
            ->select('denda.id', 'denda.denda', 'peminjaman.kode', 'users.name as nama_peminjam', 'book.title as judul_buku', 'book.isbn',  DB::raw("DATE(peminjaman.tanggal_pinjam) as tanggal_pinjam"), DB::raw("DATE(peminjaman.tanggal_batas_kembali) as tanggal_batas_kembali"), DB::raw("DATE(pengembalian.tanggal_kembali) as tanggal_kembali"), 'denda.status')
            ->paginate(10);
        return view('denda.index', compact('dendas'));
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

    public function ubah_status(Request $request, Denda $denda)
    {
        $status = $denda->status == 0 ? 1 : 0;
        $denda = DB::table('denda')
            ->where('id', $denda->id)
            ->update(['status' => $status]);
        return redirect()->back();
    }
}
