<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    protected $table = 'pengembalian';
    protected $fillable = ['id_peminjaman', 'tanggal_kembali', 'status'];
    protected $dates = ['tanggal_kembali', 'created_at', 'update_at'];
}
