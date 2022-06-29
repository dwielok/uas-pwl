<?php

namespace Database\Seeders;

use App\Models\Pengembalian;
use Illuminate\Database\Seeder;

class PengembalianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //yg ga kena denda
        Pengembalian::create([
            'id_peminjaman' => 1,
            'tanggal_kembali' => '2022-06-24',
            'status' => 1,
        ]);

        //yg kena denda
        Pengembalian::create([
            'id_peminjaman' => 2,
            'tanggal_kembali' => '2022-07-3',
            'status' => 0,
        ]);
    }
}
