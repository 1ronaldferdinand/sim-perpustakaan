<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    use HasFactory;

    protected $table = 'member';
    protected $primaryKey = 'member_id';
    public $incrementing = false;
    protected $fillable = [
        'member_id',
        'member_name',
        'member_phone',
        'member_email',
        'member_address',
        'member_image'
    ];
}
