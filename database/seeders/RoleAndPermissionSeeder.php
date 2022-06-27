<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'dashboard']);
        Permission::create(['name' => 'user.management']);
        Permission::create(['name' => 'role.permission.management']);
        Permission::create(['name' => 'menu.management']);
        Permission::create(['name' => 'book.management']);
        Permission::create(['name' => 'peminjaman.management']);
        Permission::create(['name' => 'pengembalian.management']);
        Permission::create(['name' => 'peminjaman_user.management']);
        Permission::create(['name' => 'pengembalian_user.management']);
        Permission::create(['name' => 'books_user.management']);
        Permission::create(['name' => 'denda.management']);
        Permission::create(['name' => 'laporan.management']);
        Permission::create(['name' => 'denda_user.management']);

        //user
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.create']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.destroy']);
        Permission::create(['name' => 'user.import']);
        Permission::create(['name' => 'user.export']);

        //role
        Permission::create(['name' => 'role.index']);
        Permission::create(['name' => 'role.create']);
        Permission::create(['name' => 'role.edit']);
        Permission::create(['name' => 'role.destroy']);
        Permission::create(['name' => 'role.import']);
        Permission::create(['name' => 'role.export']);

        //permission
        Permission::create(['name' => 'permission.index']);
        Permission::create(['name' => 'permission.create']);
        Permission::create(['name' => 'permission.edit']);
        Permission::create(['name' => 'permission.destroy']);
        Permission::create(['name' => 'permission.import']);
        Permission::create(['name' => 'permission.export']);

        //assignpermission
        Permission::create(['name' => 'assign.index']);
        Permission::create(['name' => 'assign.create']);
        Permission::create(['name' => 'assign.edit']);
        Permission::create(['name' => 'assign.destroy']);

        //assingusertorole
        Permission::create(['name' => 'assign.user.index']);
        Permission::create(['name' => 'assign.user.create']);
        Permission::create(['name' => 'assign.user.edit']);

        //assignbooks
        Permission::create(['name' => 'book.index']);
        Permission::create(['name' => 'book.create']);
        Permission::create(['name' => 'book.edit']);
        Permission::create(['name' => 'book.destroy']);

        //assignpeminjaman
        Permission::create(['name' => 'peminjaman.index']);
        Permission::create(['name' => 'peminjaman.create']);
        Permission::create(['name' => 'peminjaman.edit']);
        Permission::create(['name' => 'peminjaman.destroy']);

        //assignpengembalian
        Permission::create(['name' => 'pengembalian.index']);
        Permission::create(['name' => 'pengembalian.create']);
        Permission::create(['name' => 'pengembalian.edit']);
        Permission::create(['name' => 'pengembalian.destroy']);

        //assignpeminjamanuser
        Permission::create(['name' => 'peminjaman_user.index']);

        //assignpengembalianuser
        Permission::create(['name' => 'pengembalian_user.index']);

        //assignbooksuser
        Permission::create(['name' => 'books_user.index']);
        Permission::create(['name' => 'books_user.pinjam']);

        //assigndenda
        Permission::create(['name' => 'denda.index']);
        Permission::create(['name' => 'denda.create']);
        Permission::create(['name' => 'denda.edit']);
        Permission::create(['name' => 'denda.destroy']);

        //assigndendauser
        Permission::create(['name' => 'denda_user.index']);

        //assignlaporan
        Permission::create(['name' => 'laporan.index']);
        Permission::create(['name' => 'laporan.create']);
        Permission::create(['name' => 'laporan.edit']);
        Permission::create(['name' => 'laporan.destroy']);

        // create roles 
        $roleUser = Role::create(['name' => 'user']);
        $roleUser->givePermissionTo([
            'dashboard',
            'user.management',
            'user.index',
            'books_user.management',
            'books_user.index',
            'books_user.pinjam',
            'peminjaman_user.management',
            'peminjaman_user.index',
            'pengembalian_user.management',
            'pengembalian_user.index',
            'denda_user.management',
            'denda_user.index',
        ]);

        // create Super Admin
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
        $role->revokePermissionTo('peminjaman_user.management');
        $role->revokePermissionTo('peminjaman_user.index');
        $role->revokePermissionTo('pengembalian_user.management');
        $role->revokePermissionTo('pengembalian_user.index');
        $role->revokePermissionTo('denda_user.management');
        $role->revokePermissionTo('denda_user.index');
        $role->revokePermissionTo('books_user.management');
        $role->revokePermissionTo('books_user.index');
        $role->revokePermissionTo('books_user.pinjam');

        //assign user id 1 ke super admin
        $user = User::find(1);
        $user->assignRole('super-admin');
        $user = User::find(2);
        $user->assignRole('user');
    }
}
