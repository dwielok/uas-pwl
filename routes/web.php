<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\Menu\MenuGroupController;
use App\Http\Controllers\Menu\MenuItemController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PeminjamanUserController;
use App\Http\Controllers\PengembalianUserController;
use App\Http\Controllers\RoleAndPermission\AssignPermissionController;
use App\Http\Controllers\RoleAndPermission\AssignUserToRoleController;
use App\Http\Controllers\RoleAndPermission\ExportPermissionController;
use App\Http\Controllers\RoleAndPermission\ExportRoleController;
use App\Http\Controllers\RoleAndPermission\ImportPermissionController;
use App\Http\Controllers\RoleAndPermission\ImportRoleController;
use App\Http\Controllers\RoleAndPermission\PermissionController;
use App\Http\Controllers\RoleAndPermission\RoleController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

//harus login
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return view('home', ['users' => User::get(),]);
    });
    //user list

    Route::prefix('user-management')->group(function () {
        Route::resource('user', UserController::class);
        Route::post('import', [UserController::class, 'import'])->name('user.import');
        Route::get('export', [UserController::class, 'export'])->name('user.export');
        Route::get('demo', DemoController::class)->name('user.demo');
    });

    Route::prefix('menu-management')->group(function () {
        Route::resource('menu-group', MenuGroupController::class);
        Route::resource('menu-item', MenuItemController::class);
    });

    //user level beda
    Route::group(['prefix' => 'role-and-permission'], function () {
        //role
        Route::resource('role', RoleController::class);
        Route::get('role/export', ExportRoleController::class)->name('role.export');
        Route::post('role/import', ImportRoleController::class)->name('role.import');

        //permission
        Route::resource('permission', PermissionController::class);
        Route::get('permission/export', ExportPermissionController::class)->name('permission.export');
        Route::post('permission/import', ImportPermissionController::class)->name('permission.import');

        //assign permission
        Route::get('assign', [AssignPermissionController::class, 'index'])->name('assign.index');
        Route::get('assign/create', [AssignPermissionController::class, 'create'])->name('assign.create');
        Route::get('assign/{role}/edit', [AssignPermissionController::class, 'edit'])->name('assign.edit');
        Route::put('assign/{role}', [AssignPermissionController::class, 'update'])->name('assign.update');
        Route::post('assign', [AssignPermissionController::class, 'store'])->name('assign.store');

        //assign user to role
        Route::get('assign-user', [AssignUserToRoleController::class, 'index'])->name('assign.user.index');
        Route::get('assign-user/create', [AssignUserToRoleController::class, 'create'])->name('assign.user.create');
        Route::post('assign-user', [AssignUserToRoleController::class, 'store'])->name('assign.user.store');
        Route::get('assing-user/{user}/edit', [AssignUserToRoleController::class, 'edit'])->name('assign.user.edit');
        Route::put('assign-user/{user}', [AssignUserToRoleController::class, 'update'])->name('assign.user.update');
    });
    Route::prefix('book-management')->group(function () {
        Route::resource('book', BookController::class);
        Route::post('import', [BookController::class, 'import'])->name('book.import');
        Route::get('export', [BookController::class, 'export'])->name('book.export');
    });
    Route::prefix('peminjaman-management')->group(function () {
        Route::resource('peminjaman', PeminjamanController::class);
        Route::post('import', [PeminjamanController::class, 'import'])->name('peminjaman.import');
        Route::get('export', [PeminjamanController::class, 'export'])->name('peminjaman.export');
    });
    Route::prefix('pengembalian-management')->group(function () {
        Route::resource('pengembalian', PengembalianController::class);
        Route::post('import', [PengembalianController::class, 'import'])->name('pengembalian.import');
        Route::get('export', [PengembalianController::class, 'export'])->name('pengembalian.export');
    });
    Route::prefix('peminjaman-user-management')->group(function () {
        Route::resource('peminjaman_user', PeminjamanUserController::class);
    });
    Route::prefix('book-user')->group(function () {
        Route::resource('peminjaman_user', PeminjamanUserController::class);
    });
    Route::prefix('denda-management')->group(function () {
        Route::resource('denda', DendaController::class);
        Route::patch('ubah_status/{denda}', [DendaController::class, 'ubah_status'])->name('denda.ubah_status');
    });

    Route::prefix('pengembalian-user-management')->group(function () {
        Route::resource('pengembalian_user', PengembalianUserController::class);
    });
});
