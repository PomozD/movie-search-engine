<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'alternativeName',
        'start',
        'end',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
