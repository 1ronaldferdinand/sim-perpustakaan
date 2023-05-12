<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WriterModel extends Model
{
    use HasFactory;

    protected $table = 'writer';
    protected $primaryKey = 'writer_id';
    public $incrementing = false;
    protected $fillable = [
        'writer_id',
        'writer_name',
        'writer_phone',
        'writer_email',
        'writer_address',
        'writer_image'
    ];
}
