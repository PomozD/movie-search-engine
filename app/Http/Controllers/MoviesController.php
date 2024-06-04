<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionMovie;
use App\Models\Movie;
use App\Models\Rating;
use App\Models\Type;
use App\Models\Vote;
use App\Models\Fee;
use App\Models\Person;
use App\Models\Genre;
use App\Models\Countrie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Joomla\Http\Http;

class MoviesController extends Controller
{
    public static function getRecommendationMovies(Request $request) {
        try {
            $currentPage = $request['currentPage'];
            $selectedGenres = explode(',', $request['selectedGenres']);
            $selectedYears = explode(',', $request['selectedYears']);
            $selectedCountries = explode(',', $request['selectedCountries']);

            $ratingFrom = (float) $request['ratingFrom'];
            $ratingTo = (float) $request['ratingTo'];

            $searchQuery = $request['searchQuery'];

            $restrict = $request['ageConfirm'];

            $query = Movie::select('movies.*', 'posters.url as poster', 'backdrops.url as backdrop', 'logos.url as logo')
                        ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
                        ->leftJoin('posters', 'movies.id', '=', 'posters.movie_id')
                        ->leftJoin('backdrops', 'movies.id', '=', 'backdrops.movie_id')
                        ->leftJoin('logos', 'movies.id', '=', 'logos.movie_id')
                        ->with('genres')
                        ->with('restrict')
                        ->with('type')
                        ->with('countries')
                        ->groupBy('movies.id', 'posters.url', 'backdrops.url', 'logos.url')
                        ->havingRaw('COUNT(ratings.id) > 2 OR SUM(movies.votesKp) > 100')
                        ->orderByDesc('votesKp')
                        ->orderByDesc('ratingKp');

            if($restrict === 'false') {
                $query->whereHas('restrict', function ($query) {
                    $query->where('restriction', '<', '18')->orWhere('restriction', '>', '21');
                });
            }

            if ($selectedGenres[0] !== "") {
                $query->whereHas('genres', function($q) use ($selectedGenres) {
                    $q->whereIn('name', $selectedGenres);
                });
            }

            if ($selectedYears[0] !== "") {
                $query->whereIn('year', $selectedYears);
            }

            if ($selectedCountries[0] !== "") {
                $query->whereHas('countries', function($q) use ($selectedCountries) {
                    $q->whereIn('country', $selectedCountries);
                });
            }

            if (!empty($ratingFrom) && !empty($ratingTo)) {
                $query->whereBetween('ratingKp', [$ratingFrom, $ratingTo]);
            } elseif (!empty($ratingFrom)) {
                $query->where('ratingKp', '>=', $ratingFrom);
            } elseif (!empty($ratingTo)) {
                $query->where('ratingKp', '<=', $ratingTo);
            }

            if (!empty($searchQuery)) {
                $query->where('movies.name', 'like', '%' . $searchQuery . '%');
            }

            $recommendedMovies = $query->paginate(1, ['*'], 'page', $currentPage);

            $recommendedMoviesData = [];


            if ($recommendedMovies->isNotEmpty()) {
                foreach ($recommendedMovies as $movie) {
                    $movieData = $movie->toArray();
                    $movieData['fees'] = Fee::where('movie_id', $movie->id)->get()->toArray();
                    $movieData['persons'] = Person::where('movie_id', $movie->id)->get()->toArray();
                    $recommendedMoviesData[] = $movieData;
                }

                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "data" => $recommendedMoviesData,
                ];
            } else {
                return [
                    "status" => false,
                    "type" => 1,
                    "message" => "Таких фильмов нет",
                    "data" => $recommendedMovies,
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function calculateRating(Request $request) {
        try {
            $userID = $request['userID'];
            $movieID = $request['movieID'];
            $userRating = $request['rating'];

            $existingRating = Rating::where('movie_id', $movieID)
                ->where('user_id', $userID)
                ->first();

            if ($existingRating) {
                $existingRating->rating = $userRating;
                $existingRating->save();
            } else {
                Rating::create([
                    'user_id' => $userID,
                    'movie_id' => $movieID,
                    'rating' => $userRating,
                ]);
            }

            /*$existingRatingsCount = Rating::where('movie_id', $movieID)->count();
            $existingRatingsSum = Rating::where('movie_id', $movieID)->sum('rating');

            $newRatingSum = $existingRatingsSum + $userRating;
            $newRatingsCount = $existingRatingsCount + 1;

            $averageRating = $newRatingSum / $newRatingsCount;
            $averageRating = round($averageRating, 1);

            // Создаем новую запись рейтинга
            Rating::create([
                'movie_id' => $movieID,
                'rating' => $userRating,
            ]);*/

            $newAverageRating = Rating::where('movie_id', $movieID)->avg('rating');
            $newAverageRating = round($newAverageRating, 1);

            return [
                "status" => true,
                "type" => 0,
                "message" => "Рейтинг успешно обновлен",
                "data" => $newAverageRating,
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function getRating(Request $request) {
        try {
            $id = $request['id'];

            $existingRatingsCount = Rating::where('movie_id', $id)->count();

            if(!$existingRatingsCount) {
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "Рейтинга еще нет",
                    "data" => 'Рейтинга еще нет',
                ];
            }

            $existingRatingsCount = Rating::where('movie_id', $id)->count();
            $existingRatingsSum = Rating::where('movie_id', $id)->sum('rating');

            $newRatingSum = $existingRatingsSum;
            $newRatingsCount = $existingRatingsCount;

            $averageRating = $newRatingSum / $newRatingsCount;
            $averageRating = round($averageRating, 1);

            return [
                "status" => true,
                "type" => 0,
                "message" => "Рейтинг успешно просчитан",
                "data" => $averageRating,
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function addCollection(Request $request) {
        try {
            $userID = $request['userID'];
            $name = $request['name'];
            $description = $request['description'];

            Collection::create([
                'user_id' => $userID,
                'name' => $name,
                'description' => $description,
            ]);

            return [
                "status" => true,
                "type" => 0,
                "message" => "Подборка добавлена",
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function getCollections(Request $request) {
        try {
            $userID = $request['userID'];

            $collections = Collection::where('user_id', $userID)->get();

            $data = [];

            foreach($collections as $collection) {
                $collectionMovies = CollectionMovie::where('collection_id', $collection->id)->get();
                $movieData = [];

                if($collectionMovies) {
                    foreach($collectionMovies as $currentCollectionMovies) {
                        $movieData[] = Movie::select('movies.*', 'backdrops.url')
                                           ->join('backdrops', 'movies.id', '=', 'backdrops.movie_id')
                                           ->where('movies.id', $currentCollectionMovies->movie_id)
                                           ->first();

                        if(!$movieData) {
                            $movieData[] = null;
                        }
                    }
                } else {
                    $collectionMovies = null;
                    $movieData[] = null;
                }

                $data[] = [
                    'collection' => $collection,
                    'collectionMovie' => $collectionMovies,
                    'movie' => $movieData,
                ];
            }


            if($collections) {
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "data" => $data,
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function getCollectionsMovie(Request $request) {
        try {
            $userID = $request['userID'];
            $collectionID = $request['id'];

            $collection = Collection::where('user_id', $userID)
                                        ->where('id', $collectionID)
                                        ->first();

            $data = [];

            $collectionMovies = CollectionMovie::where('collection_id', $collectionID)->get();
            $movieData = [];

            if($collectionMovies) {
                foreach($collectionMovies as $currentCollectionMovies) {
                    $movieData[] = Movie::select('movies.*', 'posters.url')
                                       ->join('posters', 'movies.id', '=', 'posters.movie_id')
                                       ->where('movies.id', $currentCollectionMovies->movie_id)
                                       ->first();

                    if(!$movieData) {
                        $movieData[] = null;
                    }
                }
            } else {
                $collectionMovies = null;
                $movieData[] = null;
            }

            $data[] = [
                'collection' => $collection,
                'collectionMovie' => $collectionMovies,
                'movie' => $movieData,
            ];


            if($collectionMovies) {
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "data" => $data,
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function searchMovies(Request $request) {
        try {
            $search = $request['search'];
            $page = $request['page'] ?? 1;

            $selectedGenres = explode(',', $request['selectedGenres']);
            $selectedYears = explode(',', $request['selectedYears']);
            $selectedCountries = explode(',', $request['selectedCountries']);

            $ratingFrom = (float) $request['ratingFrom'];
            $ratingTo = (float) $request['ratingTo'];

            $query = Movie::select(
                'movies.*',
                'posters.url as poster',
                'backdrops.url as backdrop',
                'logos.url as logo',
                DB::raw('AVG(ratings.rating) as ratingMoviq'),
                DB::raw('COUNT(ratings.id) as votesMoviq')
            )
            ->leftJoin('ratings', 'movies.id', '=', 'ratings.movie_id')
            ->leftJoin('posters', 'movies.id', '=', 'posters.movie_id')
            ->leftJoin('backdrops', 'movies.id', '=', 'backdrops.movie_id')
            ->leftJoin('logos', 'movies.id', '=', 'logos.movie_id')
            ->with('genres')
            ->with('restrict')
            ->with('type')
            ->with('countries')
            ->groupBy('movies.id', 'posters.url', 'backdrops.url', 'logos.url')
            ->havingRaw('COUNT(ratings.id) > 2 OR SUM(movies.votesKp) > 100')
            ->orderByRaw('CASE WHEN COUNT(ratings.id) > 2 THEN COUNT(ratings.id) ELSE votesKp END DESC');

            if ($selectedGenres[0] !== "") {
                $query->whereHas('genres', function($q) use ($selectedGenres) {
                    $q->whereIn('name', $selectedGenres);
                });
            }

            if ($selectedYears[0] !== "") {
                $query->whereIn('year', $selectedYears);
            }

            if ($selectedCountries[0] !== "") {
                $query->whereHas('countries', function($q) use ($selectedCountries) {
                    $q->whereIn('country', $selectedCountries);
                });
            }

            if (!empty($ratingFrom) && !empty($ratingTo)) {
                $query->havingRaw('CASE WHEN COUNT(ratings.id) > 2 THEN AVG(ratings.rating) BETWEEN ? AND ? ELSE ratingKp BETWEEN ? AND ? END', [$ratingFrom, $ratingTo, $ratingFrom, $ratingTo]);
            } elseif (!empty($ratingFrom)) {
                $query->havingRaw('CASE WHEN COUNT(ratings.id) > 2 THEN AVG(ratings.rating) >= ? ELSE ratingKp >= ? END', [$ratingFrom, $ratingFrom]);
            } elseif (!empty($ratingTo)) {
                $query->havingRaw('CASE WHEN COUNT(ratings.id) > 2 THEN AVG(ratings.rating) <= ? ELSE ratingKp <= ? END', [$ratingTo, $ratingTo]);
            }

            if (!empty($search)) {
                $query->where('movies.name', 'like', '%' . $search . '%');
            }

            $movie = $query->paginate(4, ['*'], 'page', $page);

            /* $movie = $query->where('name', 'like', "%$search%")->paginate(4, ['*'], 'page', $page); */

            if($movie) {
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "data" => $movie,
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function addMovieToCollection(Request $request) {
        try {
            $userID = $request['userID'];
            $collectionID = $request['collectionID'];
            $movieID = $request['movieID'];

            $existingCollection = Collection::where('id', $collectionID)
                                            ->where('user_id', $userID)
                                            ->first();
            if($existingCollection) {
                CollectionMovie::create([
                    'collection_id' => $collectionID,
                    'movie_id' => $movieID,
                ]);

                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "Фильм добавлен в подборку",
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

     public static function deleteMovieFromCollection(Request $request) {
        try {
            $userID = $request['userID'];
            $collectionID = $request['collectionID'];
            $movieID = $request['movieID'];

            $existingCollection = Collection::where('id', $collectionID)
                                            ->where('user_id', $userID)
                                            ->first();
            if($existingCollection) {
                $collectionMovie = CollectionMovie::where('collection_id', $collectionID)
                                                    ->where('movie_id', $movieID)
                                                    ->first();

                if($collectionMovie) {
                    $collectionMovie->delete();

                    return [
                        "status" => true,
                        "type" => 0,
                        "message" => "Фильм удален из подборки",
                    ];
                } else {
                    return [
                      "status" => false,
                      "type" => 1,
                      "message" => "Фильм не найден в подборке",
                    ];
                }
            } else {
                return [
                    "status" => false,
                    "type" => 1,
                    "message" => "Подборка не найдена",
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function deleteCollectionFromCollections(Request $request) {
        try {
            $userID = $request['userID'];
            $collectionID = $request['collectionID'];

            $existingCollection = Collection::where('id', $collectionID)
                                            ->where('user_id', $userID)
                                            ->first();
            if($existingCollection) {
                $existingCollection->delete();

                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "Подборка удалена",
                ];
            } else {
                return [
                    "status" => false,
                    "type" => 1,
                    "message" => "Подборка не найдена",
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function getMovie(Request $request) {
        try {
            $id = $request->input('id');

            $movie = Movie::select('movies.*', 'posters.url as poster', 'backdrops.url as backdrop', 'logos.url as logo')
                            ->leftJoin('posters', 'movies.id', '=', 'posters.movie_id')
                            ->leftJoin('backdrops', 'movies.id', '=', 'backdrops.movie_id')
                            ->leftJoin('logos', 'movies.id', '=', 'logos.movie_id')
                            ->with('genres')
                            ->with('restrict')
                            ->with('type')
                            ->with('countries')
                            ->where('movies.id', $id)
                            ->first();

            $fees = Fee::where('movie_id', $id)->get();
            $movie->fees = $fees;

            $persons = Person::where('movie_id', $id)->get();
            $movie->persons = $persons;

            if(!$movie) {
                return [
                    "status" => false,
                    "type" => 1,
                    "message" => "Фильм не найден"
                ];
            }

            $existingRatingsCount = Rating::where('movie_id', $id)->count();
            if($existingRatingsCount > 0) {
                $existingRatingsSum = Rating::where('movie_id', $id)->sum('rating');

                $averageRating = $existingRatingsSum / $existingRatingsCount;
                $averageRating = round($averageRating, 1);
            } else {
                $averageRating = 0;
            }

            $movie->rating = $averageRating;

            if($movie) {
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "data" => $movie,
                ];
            }
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }

    public static function getMoviesData() {
        try {

            $genres = Genre::select('name')->whereNotNull('name')->get()->pluck('name');
            $years = Movie::whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year');
            $countries = Movie::with('countries')->get()->pluck('countries.*.country')->flatten()->unique();

            $data = ['genres' => $genres, 'years' => $years, 'countries' => $countries];

            return [
                "status" => true,
                "type" => 0,
                "message" => "200 ok",
                "data" => $data,
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage() // Возвращаем сообщение об ошибке
            ];
        }
    }
}
