<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'russia',
        'world'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}