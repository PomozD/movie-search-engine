<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmCountrie extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'country_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function country()
    {
        return $this->belongsTo(Countrie::class, 'country_id');
    }
}
