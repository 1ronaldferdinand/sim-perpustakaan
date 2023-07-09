<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublisherModel extends Model
{
    use HasFactory;

    protected $table = 'publisher';
    protected $primaryKey = 'publisher_id';
    public $incrementing = false;
    protected $fillable = [
        'publisher_id',
        'publisher_name',
        'publisher_phone',
        'publisher_email',
        'publisher_city',
        'publisher_address',
        'publisher_image'
    ];
}
