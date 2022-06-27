<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_admin_can_create_book()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::find(1); //mencari user yang pertama
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        //post menuju ke halaman create
        $response = $this->get('/book-management/book/create');
        $response->assertStatus(200);
        //menampilkan tulisan pada form crate book
        $response->assertSeeText('Tambah Book');
        $response->assertSeeText('ISBN');
        $response->assertSeeText('Title');
        $response->assertSeeText('Author');
        $response->assertSeeText('Book Image');
        $response->assertSeeText('Publication Date Book');
        $response->assertSeeText('Book File');
        $response->assertSeeText('User');
        //mengisikan form tambah buku
        $response = $this->post('book-management/book', [
            'isbn' => '987654321',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'image' => UploadedFile::fake()->image('bookimage.jpeg'),
            'publication_date' => '2022-01-01',
            'file' => UploadedFile::fake()->create('book.pdf', 300),
            'user_id' => 1,
        ]);
        //mengembalikan ke halaman list buku
        $response->assertRedirect('/book-management/book');
        //
        $response = $this->get('/book-management/book');
        //menampilkan tulisan dari inputan
        $response->assertSeeText('987654321');
        $response->assertSeeText('Judul Buku');
        $response->assertSeeText('Jono');
        $response->assertSeeText('01-01-2022');
        $response->assertSeeText('SuperAdmin');
    }

    public function test_admin_can_show_book()
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
        //post menuju ke halaman list buku
        $response = $this->get('/book-management/book');
        $response->assertStatus(200);
        //menampilkan tulisan edit & delete pada halaman list buku
        $response->assertSeeText('Edit');
        $response->assertSeeText('Delete');
    }

    public function test_admin_can_update_book()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        //crate book
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
        //mengganti data
        $response = $this->put("book-management/book/{$book->id}", [
            'isbn' => '123456789',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'publication_date' => '2022-01-01',
            'user_id' => 1,
        ]);
        //mengembalikan ke list book
        $response->assertRedirect('/book-management/book');
        $response = $this->get('/book-management/book');
        $response->assertStatus(200);
        //menampilkan tulisan data terbaru
        $response->assertSeeText('123456789');
    }

    public function test_admin_can_delete_book()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
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
        //mengapus book
        $response = $this->delete("/book-management/book/{$book->id}");
        //mengembalikan ke list book
        $response->assertRedirect('/book-management/book');
        $response = $this->get('/book-management/book');
        $response->assertStatus(200);
        //tidak menampilkan tulisan isbn yang dihapus barusan
        $response->assertDontSeeText('987654321');
    }

    public function test_admin_cannot_open_books_user()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
        $response = $this->actingAs($user);
        $response = $this->get('/book-user-management/book-user');
        $response->assertStatus(403);
    }

    public function test_admin_cannot_open_pinjam_di_books_user()
    {
        // $this->seed();
        $user = User::where('email', 'superadmin@gmail.com')->first();
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
        $response->assertStatus(403);
    }

    public function test_admin_cannot_pinjam_di_books_user()
    {
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
        $response = $this->post('book-user-management/book/pinjam', [
            'id_user' => $user->id,
            'id_buku' => $book->id,
            'tanggal_pinjam' => '2022-06-21',
            'tanggal_batas_kembali' => '2022-06-22',
        ]);
        $response = $this->get('/peminjaman-user-management/peminjaman_user');
        $response->assertStatus(403);
    }

    public function test_user_can_show_book()
    {
        // $this->seed();
        //validasi login dengan superadmin
        $user = User::where('email', 'user@gmail.com')->first();
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
        //post menuju ke halaman list buku
        $response = $this->get('/book-user-management/book-user');
        $response->assertStatus(200);
        //menampilkan tulisan edit & delete pada halaman list buku
        $response->assertSeeText('987654321');
        $response->assertSeeText('Judul Buku');
    }

    public function test_user_cannot_insert_book()
    {
        // $this->seed();
        //validasi login dengan user
        $user = User::where('email', 'user@gmail.com')->first();
        $response = $this->actingAs($user);
        //post menuju ke halaman create
        $response = $this->get('/book-management/book/create');
        $response->assertStatus(403);
        $response = $this->post('book-management/book', [
            'isbn' => '987654321',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'image' => UploadedFile::fake()->image('bookimage.jpeg'),
            'publication_date' => '2022-01-01',
            'file' => UploadedFile::fake()->create('book.pdf', 300),
            'user_id' => 1,
        ]);
        //mengembalikan ke halaman list buku
        $response->assertStatus(403);
    }

    public function test_user_cannot_update_book()
    {
        // $this->seed();
        $user = User::where('email', 'user@gmail.com')->first();
        $response = $this->actingAs($user);
        //crate book
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
        //mengganti data
        $response = $this->put("book-management/book/{$book->id}", [
            'isbn' => '123456789',
            'title' => 'Judul Buku',
            'author' => 'Jono',
            'publication_date' => '2022-01-01',
            'user_id' => 1,
        ]);
        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_book()
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
        //mengapus book
        $response = $this->delete("/book-management/book/{$book->id}");
        $response->assertStatus(403);
    }
}
