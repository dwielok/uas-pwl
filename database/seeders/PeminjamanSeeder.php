<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Peminjaman::create([
            'kode' => 'LIB-123',
            'id_user' => 2,
            'id_buku' => 1,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-28',
        ]);
        Peminjaman::create([
            'kode' => 'LIB-456',
            'id_user' => 2,
            'id_buku' => 2,
            'tanggal_pinjam' => '2022-06-30',
            'tanggal_batas_kembali' => '2022-07-01',
        ]);
    }
}
