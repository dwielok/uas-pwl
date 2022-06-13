<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengembalianRequest;
use App\Http\Requests\UpdatePengembalianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;

class PengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pengembalian.index')->only('index');
        $this->middleware('permission:pengembalian.create')->only('create', 'store');
        $this->middleware('permission:pengembalian.edit')->only('edit', 'update');
        $this->middleware('permission:pengembalian.destroy')->only('destroy');
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
            ->paginate(10);
        return view('pengembalian.index', compact('pengembalians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $peminjamans = Peminjaman::all();
        return view('pengembalian.create', compact('peminjamans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePengembalianRequest $request)
    {
        $id_peminjaman = explode('#', $request->id_peminjaman)[0];
        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $id_peminjaman,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => $request->status,
        ]);
        $book = Peminjaman::where('id', $id_peminjaman)->first();
        Book::where('id', $book->id_buku)->update(['status' => 1]);
        if($request->status == 0){
            Denda::create([
                'id_peminjaman' => $id_peminjaman,
                'id_pengembalian' => $pengembalian->id,
                'id_buku' => $book->id_buku,
                'id_user' => $book->id_user,
                'denda' => $request->total_hari * 50000,
                'status' => 0
            ]);
        }
        return redirect(route('pengembalian.index'))->with('success', 'Data Berhasil Ditambahkan');;
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
    public function edit(Pengembalian $pengembalian)
    {
        $peminjamans = Peminjaman::where('id', $pengembalian->id_peminjaman)->first();
        return view('pengembalian.edit', compact('peminjamans', 'pengembalian'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePengembalianRequest $request, Pengembalian $pengembalian)
    {
        $validate = $request->validated();

        $pengembalian->update($validate);

        if($request->status == 0){
            $denda = Denda::where('id_pengembalian', $pengembalian->id)->count();
            if($denda == 0){
                $book = Peminjaman::where('id', $pengembalian->id_peminjaman)->first();
                Denda::create([
                    'id_peminjaman' => $pengembalian->id_peminjaman,
                    'id_pengembalian' => $pengembalian->id,
                    'id_buku' => $book->id_buku,
                    'id_user' => $book->id_user,
                    'denda' => $request->total_hari * 50000,
                    'status' => 0
                ]);
            } else {
                Denda::where('id_pengembalian', $pengembalian->id)->update([
                    'denda' => $request->total_hari * 50000,
                    'status' => 0
                ]);
            }
        }else {
            Denda::where('id_pengembalian', $pengembalian->id)->delete();
        }
        return redirect(route('pengembalian.index'))->with('success', 'Data Berhasil Diedit');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengembalian $pengembalian)
    {
        $pengembalian->delete();
        Denda::where('id_peminjaman', $pengembalian->id_peminjaman)->delete();
        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian Deleted Successfully');
    }
}
