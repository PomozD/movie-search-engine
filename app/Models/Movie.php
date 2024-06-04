<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'apiID',
        'externalID',
        'name',
        'alternativeName',
        'description',
        'shortDescription',
        'slogan',
        'year',
        'isSeries',
        'ratingKp',
        'ratingMpaa',
        'votesKp',
        'seasonInfo',
        'budget',
        'movieLength',
        'seriesLength',
        'totalSeriesLength',
        'updatedAtDate',
        'createdAtDate',
        'type_id',
        'restrict_id',
        'external_id',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function restrict()
    {
        return $this->belongsTo(Restrict::class, 'restrict_id');
    }

    public function external()
    {
        return $this->belongsTo(External_ID::class, 'external_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'film_genres', 'movie_id', 'genre_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Countrie::class, 'film_countries', 'movie_id', 'country_id');
    }
}
