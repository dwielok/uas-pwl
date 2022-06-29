<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // MenuItem::factory()->count(10)->create();
        MenuItem::insert(
            [
                [
                    'name' => 'Dashboard',
                    'route' => 'dashboard',
                    'permission_name' => 'dashboard',
                    'menu_group_id' => 1,
                ],
                [
                    'name' => 'User List',
                    'route' => 'user-management/user',
                    'permission_name' => 'user.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Role List',
                    'route' => 'role-and-permission/role',
                    'permission_name' => 'role.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'Permission List',
                    'route' => 'role-and-permission/permission',
                    'permission_name' => 'permission.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'Permission To Role',
                    'route' => 'role-and-permission/assign',
                    'permission_name' => 'assign.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'User To Role',
                    'route' => 'role-and-permission/assign-user',
                    'permission_name' => 'assign.user.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'Menu Group',
                    'route' => 'menu-management/menu-group',
                    'permission_name' => 'menu-group.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'Menu Item',
                    'route' => 'menu-management/menu-item',
                    'permission_name' => 'menu-item.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'Book List',
                    'route' => 'book-management/book',
                    'permission_name' => 'book.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'List Peminjaman',
                    'route' => 'peminjaman-management/peminjaman',
                    'permission_name' => 'peminjaman.index',
                    'menu_group_id' => 6,
                ],
                [
                    'name' => 'List Pengembalian',
                    'route' => 'pengembalian-management/pengembalian',
                    'permission_name' => 'pengembalian.index',
                    'menu_group_id' => 7,
                ],
                [
                    'name' => 'List Peminjaman User',
                    'route' => 'peminjaman-user-management/peminjaman_user',
                    'permission_name' => 'peminjaman_user.index',
                    'menu_group_id' => 8,
                ],
                [
                    'name' => 'Denda List',
                    'route' => 'denda-management/denda',
                    'permission_name' => 'denda.index',
                    'menu_group_id' => 9,
                ],
                [
                    'name' => 'List Pengembalian User',
                    'route' => 'pengembalian-user-management/pengembalian_user',
                    'permission_name' => 'pengembalian_user.index',
                    'menu_group_id' => 10,
                ],
                [
                    'name' => 'Book List User',
                    'route' => 'book-user-management/book-user',
                    'permission_name' => 'books_user.index',
                    'menu_group_id' => 11,
                ],
                [
                    'name' => 'Peminjaman per User',
                    'route' => 'laporan-management/laporan/peminjaman_per_user',
                    'permission_name' => 'laporan.peminjaman_per_user',
                    'menu_group_id' => 12,
                ],
                [
                    'name' => 'Pengembaian per User',
                    'route' => 'laporan-management/laporan/pengembalian_per_user',
                    'permission_name' => 'laporan.pengembalian_per_user',
                    'menu_group_id' => 12,
                ],
                [
                    'name' => 'Denda per User',
                    'route' => 'laporan-management/laporan/denda_per_user',
                    'permission_name' => 'laporan.denda_per_user',
                    'menu_group_id' => 12,
                ],
                [
                    'name' => 'Denda List User',
                    'route' => 'denda-user-management/denda_user',
                    'permission_name' => 'denda_user.index',
                    'menu_group_id' => 13,
                ]
            ]
        );
    }
}
