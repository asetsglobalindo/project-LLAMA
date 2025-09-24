<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'slug',
        'image',
        'content',
        'category',
        'order',
        'tags',
        'published_date',
        'status'
    ];
}
