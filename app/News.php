<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class News extends Model
{
    protected $fillable = [
        'title', 'body', 'created_by', 'images'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments() {
        return $this->hasMany(NewsComment::class);
    }
}
