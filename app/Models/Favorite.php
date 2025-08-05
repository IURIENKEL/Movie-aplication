<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'movie_id', 'title', 'poster_path', 'genre_ids',
    ];

    protected $casts = [
        'genre_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
