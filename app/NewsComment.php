<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{

    protected $fillable = [
        'text', 'user_id', 'news_id'
    ];

    public function news() {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
