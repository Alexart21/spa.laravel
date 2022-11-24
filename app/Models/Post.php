<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    const NEW_STATUS = 0;
    const READ_STATUS = 1;

//    protected $guarded = [];
    protected $fillable = [
        'name',
        'email',
        'tel',
        'body'
    ];
}
