<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimilarMovie extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'alternativeName',
        'enName',
        'apiID',
        'name',
        'year',
        'type_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}
