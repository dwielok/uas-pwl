<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = 'book';
    protected $fillable = ['user_id', 'isbn', 'title', 'image', 'author', 'publication_date', 'read','file'];
    protected $dates = ['publication_date', 'created_at', 'update_at'];
}
