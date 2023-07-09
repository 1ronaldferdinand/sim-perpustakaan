<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowModel extends Model
{
    use HasFactory;
    protected $table = 'borrow';
    protected $primaryKey = 'borrow_id';
    public $incrementing = false;
    protected $fillable = [
        'borrow_id',
        'book_id',
        'member_id',
        'borrow_amount',
        'borrow_start',
        'borrow_end'
    ];

    // Define the relationship with the "book" table
    public function book()
    {
        return $this->belongsTo(BookModel::class, 'book_id');
    }

    // Define the relationship with the "member" table
    public function member()
    {
        return $this->belongsTo(MemberModel::class, 'member_id');
    }
}
