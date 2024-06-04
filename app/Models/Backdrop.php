<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backdrop extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'previewUrl',
        'url',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
