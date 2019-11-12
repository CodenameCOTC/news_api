<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    public function news() {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
