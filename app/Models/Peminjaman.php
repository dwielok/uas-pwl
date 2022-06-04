<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';
    protected $fillable = ['kode','id_buku', 'id_user', 'tanggal_pinjam', 'tanggal_batas_kembali', 'status'];
    protected $dates = ['tanggal_pinjam', 'tanggal_batas_kembali', 'created_at', 'update_at'];
}
