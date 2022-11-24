<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'content';
    protected $guarded = [];

    /*protected $fillable = [
        'page_text',
        'title',
        'title_seo',
        'description',
        'created_at',
        'updated_at',
    ];*/
}
