<?php

namespace Database\Seeders;

use App\Models\Denda;
use Illuminate\Database\Seeder;

class DendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Denda::create([
            'id_peminjaman' => 2,
            'id_pengembalian' => 2,
            'id_buku' => 2,
            'denda' => 100000,
            'id_user' => 2,
            'status' => 0
        ]);
    }
}
