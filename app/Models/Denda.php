<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;
    protected $table = 'denda';
    protected $fillable = ['id_peminjaman', 'id_pengembalian', 'id_buku', 'id_user', 'denda', 'status'];
}
