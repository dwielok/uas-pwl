<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PeminjamanTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_admin_can_create_peminjaman()
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
        //post menuju ke halaman create
        $response = $this->get('/peminjaman-management/peminjaman/create');
        $response->assertStatus(200);
        //menampilkan tulisan pada form crate book
        $response->assertSeeText('Tambah Peminjaman');
        $response->assertSeeText('Peminjam');
        $response->assertSeeText('Buku');
        $response->assertSeeText('Tanggal Pinjam');
        $response->assertSeeText('Tanggal Batas Kembali');
        //mengisikan form tambah peminjaman
        $response = $this->post('peminjaman-management/peminjaman', [
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        //mengembalikan ke halaman list peminjaman
        $response->assertRedirect('/peminjaman-management/peminjaman');
        $response = $this->get('/peminjaman-management/peminjaman');
        //menampilkan tulisan dari inputan
        $response->assertSeeText('user');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('987654321');
        $response->assertSeeText('21-06-2022');
        $response->assertSeeText('22-06-2022');
    }

    public function test_admin_can_show_peminjaman()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        //create book
        $book = Book::create([
            'isbn' => '987654321',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'image' => 'image.jpg',
            'publication_date' => '2022-01-01',
            'file' => 'test.pdf',
            'user_id' => 1,
            'read' => 0,
        ]);
        //create peminjaman
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        // post menuju ke halaman list peminjaman
        $response = $this->get('/peminjaman-management/peminjaman');
        $response->assertStatus(200);
        //menampilkan tulisan edit & delete pada halaman list buku
        $response->assertSeeText('Edit');
        $response->assertSeeText('Delete');
    }

    public function test_admin_can_update_peminjaman()
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
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->put("peminjaman-management/peminjaman/{$peminjaman->id}", [
            'id_user' => '1',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-24',
        ]);
        //mengembalikan ke list peminjaman
        $response->assertRedirect('/peminjaman-management/peminjaman');
        $response = $this->get('/peminjaman-management/peminjaman');
        $response->assertStatus(200);
        //menampilkan tulisan data terbaru
        $response->assertSeeText('SuperAdmin');
        $response->assertSeeText('24-06-2022');
        $response->assertDontSeeText('user');
    }

    public function test_admin_can_delete_peminjaman()
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
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->delete("/peminjaman-management/peminjaman/{$peminjaman->id}");
        //mengembalikan ke list peminjaman
        $response->assertRedirect('/peminjaman-management/peminjaman');
        $response = $this->get('/peminjaman-management/peminjaman');
        $response->assertStatus(200);
        //tidak menampilkan tulisan user yang dihapus barusan
        $response->assertDontSeeText('user');
    }

    public function test_admin_cannot_open_peminjaman_user()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        $response = $this->get('/peminjaman-user-management/peminjaman_user');
        $response->assertStatus(403);
    }

    public function test_user_can_open_pinjam_di_books_user()
    {
        // $this->seed();
        $user = User::where('email', 'user@gmail.com')->first();
        $response = $this->actingAs($user);
        $book = Book::create([
            'isbn' => '987654321',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'image' => 'image.jpg',
            'publication_date' => '2022-01-01',
            'file' => 'test.pdf',
            'user_id' => 1,
            'read' => 0,
        ]);
        $response = $this->get('/book-user-management/' . $book->id . '/pinjam');
        $response->assertStatus(200);
    }

    public function test_user_can_pinjam_di_books_user()
    {
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
        $response = $this->post('book-user-management/book/pinjam', [
            'id_user' => $user->id,
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->get('/peminjaman-user-management/peminjaman_user');
        $response->assertStatus(200);
        $response->assertSeeText('user');
        $response->assertSeeText('22-06-2022');
        $response->assertSeeText('987654321');
        $response->assertSeeText('Judul Buku');
    }

    public function test_user_can_show_peminjaman_di_peminjaman_user()
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
        //mengisikan form tambah peminjaman
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->get('/peminjaman-user-management/peminjaman_user');
        //menampilkan tulisan dari inputan
        $response->assertSeeText('user');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('987654321');
        $response->assertSeeText('21-06-2022');
        $response->assertSeeText('22-06-2022');
    }

    public function test_user_cannot_create_peminjaman()
    {
        // $this->seed();
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
        //post menuju ke halaman create
        $response = $this->get('/peminjaman-management/peminjaman/create');
        $response->assertStatus(403);
        //mengisikan form tambah peminjaman
        $response = $this->post('peminjaman-management/peminjaman', [
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        //mengembalikan ke halaman list peminjaman
        $response->assertStatus(403);
    }

    public function test_user_cannot_update_peminjaman()
    {
        // $this->seed();
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
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->put("peminjaman-management/peminjaman/{$peminjaman->id}", [
            'id_user' => '1',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-24',
        ]);
        //mengembalikan ke list peminjaman
        $response = $this->get('/peminjaman-management/peminjaman');
        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_peminjaman()
    {
        // $this->seed();
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
        $peminjaman = Peminjaman::create([
            'kode' => '123456789',
            'id_user' => '2',
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->delete("/peminjaman-management/peminjaman/{$peminjaman->id}");
        $response->assertStatus(403);
    }
}
