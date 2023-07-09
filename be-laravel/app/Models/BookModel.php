<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    use HasFactory;
    protected $table = 'book';
    protected $primaryKey = 'book_id';
    public $incrementing = false;
    protected $fillable = [
        'book_id',
        'isbn',
        'writer_id',
        'publisher_id',
        'book_name',
        'book_type',
        'book_size',
        'book_price',
        'book_stock',
        'book_publish_city',
        'book_publish_date',
        'book_print_date'
    ];
}
