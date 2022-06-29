<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Book::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->isbn10(),
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name,
            'image' => $this->faker->image(public_path('images'), 640, 480, null, false),
            'publication_date' => $this->faker->date('Y-m-d'),
            'file' => UploadedFile::fake()->create('book.pdf', 300),
            'user_id' => 1,
            'read' => 0,
        ];
    }
}
