<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // MenuGroup::factory()->count(5)->create();
        MenuGroup::insert(
            [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'permission_name' => 'dashboard',
                ],
                [
                    'name' => 'Users Management',
                    'icon' => 'fas fa-users',
                    'permission_name' => 'user.management',
                ],
                [
                    'name' => 'Role Management',
                    'icon' => 'fas fa-user-tag',
                    'permisison_name' => 'role.permission.management',
                ],
                [
                    'name' => 'Menu Management',
                    'icon' => 'fas fa-bars',
                    'permisison_name' => 'menu.management',
                ],
                [
                    'name' => 'Book Management',
                    'icon' => 'fas fa-book',
                    'permisison_name' => 'book.management',
                ],
                [
                    'name' => 'Peminjaman Management',
                    'icon' => 'fas fa-hand-holding',
                    'permisison_name' => 'peminjaman.management',
                ],
                [
                    'name' => 'Pengembalian Management',
                    'icon' => 'fas fa-hands',
                    'permisison_name' => 'pengembalian.management',
                ],
                [
                    'name' => 'Peminjaman User Management',
                    'icon' => 'fas fa-hand-holding',
                    'permisison_name' => 'peminjaman_user.management',
                ],
                [
                    'name' => 'Denda Management',
                    'icon' => 'fas fa-dollar-sign',
                    'permisison_name' => 'denda.management',
                ],
                [
                    'name' => 'Pengembalian User Management',
                    'icon' => 'fas fa-hands',
                    'permisison_name' => 'pengembalian_user.management',
                ],
                [
                    'name' => 'Book User Management',
                    'icon' => 'fas fa-book',
                    'permisison_name' => 'books_user.management',
                ],
                [
                    'name' => 'Laporan Management',
                    'icon' => 'fas fa-chart-line',
                    'permisison_name' => 'laporan.management',
                ],
                [
                    'name' => 'Denda User Management',
                    'icon' => 'fas fa-dollar-sign',
                    'permisison_name' => 'denda_user.management',
                ]
            ]
        );
    }
}
