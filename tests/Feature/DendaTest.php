<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DendaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_admin_can_show_denda()
    {
        // $this->withoutExceptionHandling();
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::find(1); //mencari user yang pertama
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        //menambahkan buku
        $book = Book::create([
            'isbn' => '987654321',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'image' => UploadedFile::fake()->image('bookimage.jpeg'),
            'publication_date' => '2022-01-01',
            'file' => UploadedFile::fake()->create('book.pdf', 300),
            'user_id' => 1,
            'read' => 0,
        ]);
        //create peminjaman
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-28',
        ]);
        //post menuju ke halaman create
        $response = $this->get('/pengembalian-management/pengembalian/create');
        $response->assertStatus(200);
        //menampilkan tulisan pada form crate book
        $response->assertSeeText('Tambah Pengembalian');
        $response->assertSeeText('Peminjaman');
        $response->assertSeeText('Tanggal Kembali');
        //mengisikan form tambah pengembalian
        $response = $this->post('pengembalian-management/pengembalian', [
            'id_peminjaman' => $peminjaman->id . '#2022-06-22',
            'tanggal_kembali' => '2022-06-30',
            'status' => 0,
            'total_hari' => 2,
        ]);
        //mengembalikan ke halaman list peminjaman
        $response->assertRedirect('/pengembalian-management/pengembalian');
        $response = $this->get('/denda-management/denda');
        //menampilkan tulisan dari inputan
        $response->assertSeeText('user');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('21-06-2022');
        $response->assertSeeText('30-06-2022');
        $response->assertSeeText('Belum Lunas');
    }

    public function test_admin_cannot_open_denda_di_user()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        $response = $this->get('/denda-user-management/denda_user');
        $response->assertStatus(403);
    }

    public function test_user_can_show_denda_di_user()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::where('email', 'user@gmail.com')->first();
        $response = $this->actingAs($user);
        //menambahkan buku
        $book = Book::create([
            'isbn' => '987654321',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'image' => UploadedFile::fake()->image('bookimage.jpeg'),
            'publication_date' => '2022-01-01',
            'file' => UploadedFile::fake()->create('book.pdf', 300),
            'user_id' => 1,
            'read' => 0,
        ]);
        //create peminjaman
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-28',
        ]);

        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $peminjaman->id,
            'tanggal_kembali' => '2022-06-30',
            'status' => 0,
            'total_hari' => 2,
        ]);

        Denda::create([
            'id_peminjaman' => $peminjaman->id,
            'id_pengembalian' => $pengembalian->id,
            'id_buku' => $book->id,
            'denda' => 10000,
            'id_user' => 1,
            'status' => 0
        ]);

        $response = $this->get('/denda-user-management/denda_user');
        $response->assertStatus(200);
        //menampilkan tulisan dari inputan
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('30-06-2022');
        $response->assertSeeText('Belum Lunas');
    }
}
