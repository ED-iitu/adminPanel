<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title', 'short_text', 'full_text', 'lang', 'image', 'event_date'
    ];
}
