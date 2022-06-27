<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PengembalianTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_admin_can_create_pengembalian()
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
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        //mengembalikan ke halaman list peminjaman
        $response->assertRedirect('/pengembalian-management/pengembalian');
        $response = $this->get('/pengembalian-management/pengembalian');
        //menampilkan tulisan dari inputan
        $response->assertSeeText('user');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('987654321');
        $response->assertSeeText('21-06-2022');
        $response->assertSeeText('23-06-2022');
        $response->assertSeeText('Tepat Waktu');
    }

    public function test_admin_can_show_pengembalian()
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

        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $peminjaman->id,
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);

        $response = $this->get('/pengembalian-management/pengembalian');
        $response->assertStatus(200);
        //menampilkan tulisan edit & delete pada halaman list buku
        $response->assertSeeText('Edit');
        $response->assertSeeText('Delete');
    }

    public function test_admin_can_update_pengembalian()
    {
        $this->withoutExceptionHandling();
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

        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $peminjaman->id,
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        $response = $this->put("pengembalian-management/pengembalian/{$pengembalian->id}", [
            'tanggal_kembali' => '2022-06-24',
            'status' => 1,
            'total_hari' => 3,
        ]);
        //mengembalikan ke list peminjaman
        $response->assertRedirect('/pengembalian-management/pengembalian');
        $response = $this->get('/pengembalian-management/pengembalian');
        $response->assertStatus(200);
        $response->assertSeeText('user');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('987654321');
        $response->assertSeeText('21-06-2022');
        $response->assertSeeText('24-06-2022');
        $response->assertSeeText('Tepat Waktu');
    }

    public function test_admin_can_delete_pengembalian()
    {
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

        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $peminjaman->id,
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        $response = $this->delete("/pengembalian-management/pengembalian/{$pengembalian->id}");
        //mengembalikan ke list peminjaman
        $response->assertRedirect('/pengembalian-management/pengembalian');
        $response = $this->get('/pengembalian-management/pengembalian');
        $response->assertStatus(200);
        //tidak menampilkan tulisan user yang dihapus barusan
        $response->assertDontSeeText('user');
    }

    public function test_admin_cannot_open_pengembalian_di_user()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        $response = $this->get('/pengembalian-user-management/pengembalian_user');
        $response->assertStatus(403);
    }

    public function test_user_can_show_pengembalian_di_pengembalian_user()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::find(1); //mencari user yang pertama
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
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        $response = $this->get('/pengembalian-user-management/pengembalian_user');
        //menampilkan tulisan dari inputan
        $response->assertSeeText('user');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('987654321');
        $response->assertSeeText('21-06-2022');
        $response->assertSeeText('23-06-2022');
    }

    public function test_user_cannot_create_pengembalian()
    {
        // $this->withoutExceptionHandling();
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::find(1); //mencari user yang pertama
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
        //post menuju ke halaman create
        $response = $this->get('/pengembalian-management/pengembalian/create');
        $response->assertStatus(403);
        //mengisikan form tambah pengembalian
        $response = $this->post('pengembalian-management/pengembalian', [
            'id_peminjaman' => $peminjaman->id . '#2022-06-22',
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        //mengembalikan ke halaman list peminjaman
        $response->assertStatus(403);
    }

    public function test_user_cannot_update_pengembalian()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::find(1); //mencari user yang pertama
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
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        $response = $this->put("pengembalian-management/pengembalian/{$pengembalian->id}", [
            'tanggal_kembali' => '2022-06-24',
            'status' => 1,
            'total_hari' => 3,
        ]);
        //mengembalikan ke list pengembalian
        $response = $this->get('/pengembalian-management/pengembalian');
        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_pengembalian()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::find(1); //mencari user yang pertama
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
            'tanggal_kembali' => '2022-06-23',
            'status' => 1,
            'total_hari' => 2,
        ]);
        $response = $this->delete("/pengembalian-management/pengembalian/{$pengembalian->id}");
        $response->assertStatus(403);
    }
}
