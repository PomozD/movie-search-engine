<?php

namespace App\Http\Controllers;

use App\Models\AddMovie;
use App\Models\Backdrop;
use App\Models\Countrie;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Poster;
use App\Models\Premiere;
use App\Models\ReleaseYear;
use App\Models\Restrict;
use App\Models\Role;
use App\Models\Trailer;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public static function getMoviesTypes() {
        $types = Type::select('name')->get();

        if ($types->isNotEmpty()) {
            $res = [
                "status" => true,
                "type" => 0,
                "message" => "200 ok",
                "items" => $types,
            ];
        } else {
            $res = [
                "status" => false,
                "type" => 1,
                "message" => "Произошла ошибка"
            ];
        }

        return $res;
    }

    public static function getMoviesRestricts() {
        $restricts = Restrict::select('restriction')->get();

        if ($restricts->isNotEmpty()) {
            $res = [
                "status" => true,
                "type" => 0,
                "message" => "200 ok",
                "items" => $restricts,
            ];
        } else {
            $res = [
                "status" => false,
                "type" => 1,
                "message" => "Произошла ошибка"
            ];
        }

        return $res;
    }

    public static function getMoviesGenres() {
        $genres = Genre::select('name')->get();

        if ($genres->isNotEmpty()) {
            $res = [
                "status" => true,
                "type" => 0,
                "message" => "200 ok",
                "items" => $genres,
            ];
        } else {
            $res = [
                "status" => false,
                "type" => 1,
                "message" => "Произошла ошибка"
            ];
        }

        return $res;
    }

    public static function addMovie(Request $request) {
        try {
            $countryMovie = $request['country'];
            $kpID = $request['kpID'];
            $nameMovie = $request['nameMovie'];
            $alternativeName = $request['alternativeName'];
            $description = $request['description'];
            $shortDescription = $request['shortDescription'];
            $slogan = $request['slogan'];
            $year = $request['year'];
            $isSeries = $request['isSeries'];
            $kpRating = $request['kpRating'];
            $ratingMpaa = $request['ratingMpaa'];
            $votesKp = $request['votesKp'];
            $budget = $request['budget'];
            $movieLength = $request['movieLength'];
            $seriesLength = $request['seriesLength'];
            $backdrop = $request['backdrop'];
            $poster = $request['poster'];
            $yearRus = $request['yearRus'];
            $yearWorld = $request['yearWorld'];
            $yearSerialStart = $request['yearSerialStart'];
            $yearSerialEnd = $request['yearSerialEnd'];
            $trailer = $request['trailer'];
            $movieSelectedTypeOption = $request['movieSelectedTypeOption'];
            $movieSelectedRestrictOption = $request['movieSelectedRestrictOption'];
            $movieSelectedGenreOption = $request['movieSelectedGenreOption'];

            $type = Type::where('name', $movieSelectedTypeOption)->first();

            if($type) {
                $typeID = $type->id;
            } else {
                throw new \InvalidArgumentException("Такого типа не существует");
            }

            $restrict = Restrict::where('restriction', $movieSelectedRestrictOption)->first();

            if($restrict) {
                $restrictID = $restrict->id;
            } else {
                throw new \InvalidArgumentException("Такого ограничения не существует");
            }


            $genre = Genre::where('name', $movieSelectedGenreOption)->first();

            if($genre) {
                $genreID = $genre->id;
            } else {
                throw new \InvalidArgumentException("Такого жанра не существует");
            }

            $countryID = null;

            $addCountry = Countrie::where('country', $countryMovie)->first();

            if (!$addCountry) {
                $country = Countrie::create([
                    'country' => $countryMovie,
                ]);

                if($country) {
                    $countryID = $country->id;
                } else {
                    throw new \InvalidArgumentException("Страна не добавлена");
                }
            } else {
                $countryID = $addCountry->id;
            }

            $movie = Movie::create([
                'externalID' => $kpID,
                'name' => $nameMovie,
                'alternativeName' => $alternativeName,
                'description' => $description,
                'shortDescription' => $shortDescription,
                'slogan' => $slogan,
                'year' => $year,
                'isSeries' => $isSeries,
                'ratingKp' => $kpRating,
                'ratingMpaa' => $ratingMpaa,
                'votesKp' => $votesKp,
                'budget' => $budget,
                'movieLength' => $movieLength,
                'seriesLength' => $seriesLength,

                'type_id' => $typeID,
                'restrict_id' => $restrictID,
            ]);

            if(!$movie) {
                throw new \Exception("Запись не была создана");
            }

            $movie->genres()->attach($genreID);
            $movie->countries()->attach($countryID);

            if (isset($backdrop)) {
                $addBackdrop = Backdrop::create([
                    'movie_id' => $movie->id,
                    'url' => $backdrop,
                ]);
                if (!$addBackdrop) {
                    throw new \Exception("Данные по постеру не добавлены");
                }
            }

            if (isset($poster)) {
                $addPoster = Poster::create([
                    'movie_id' => $movie->id,
                    'url' => $poster,
                ]);
                if (!$addPoster) {
                    throw new \Exception("Данные по постеру не добавлены");
                }
            }

            $premiere = Premiere::create([
                'movie_id' => $movie->id,
                'russia' => $yearRus ?? null,
                'world' => $yearWorld ?? null,
            ]);
            if (!$premiere) {
                throw new \Exception("Данные по премьере не добавлены");
            }

            $releaseYears = ReleaseYear::create([
                'movie_id' => $movie->id,
                'start' => $yearSerialStart ?? null,
                'end' => $yearSerialEnd ?? null,
            ]);
            if (!$releaseYears) {
                throw new \Exception("Данные по дате релиза не добавлены");
            }

            if (isset($trailer)) {
                $addTrailer = Trailer::create([
                    'movie_id' => $movie->id,
                    'url' => $trailer,
                ]);
                if (!$addTrailer) {
                    throw new \Exception("Данные по трейлеру не добавлены");
                }
            }

            return [
                "status" => true,
                "type" => 0,
                "message" => 'Добавлен новый фильм',
            ];

        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function addCountry(Request $request) {
        try {
            $newCountry = $request['newCountry'];

            $addCountry = Countrie::where('country', $newCountry)->first();

            if (!$addCountry) {
                $country = Countrie::create([
                    'country' => $newCountry,
                ]);

                if(!$country) {
                    throw new \InvalidArgumentException("Произошла ошибка при добавлении страны");
                }
            } else {
                throw new \InvalidArgumentException("Страна уже существует");
            }

            return [
                "status" => true,
                "type" => 0,
                "message" => 'Добавлена новая страна',
            ];

        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function addGenre(Request $request) {
        try {
            $newGenre = $request['newGenre'];

            $addGenre = Genre::where('name', $newGenre)->first();

            if (!$addGenre) {
                $genre = Genre::create([
                    'name' => $newGenre,
                ]);

                if(!$genre) {
                    throw new \InvalidArgumentException("Произошла ошибка при добавлении жанра");
                }
            } else {
                throw new \InvalidArgumentException("Жанр уже существует");
            }

            return [
                "status" => true,
                "type" => 0,
                "message" => 'Добавлен новый жанр',
            ];

        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function addRestrict(Request $request) {
        try {
            $newRestrict = $request['newRestrict'];

            $addRestrict = Restrict::where('restriction', $newRestrict)->first();

            if (!$addRestrict) {
                $restrict = Restrict::create([
                    'restriction' => $newRestrict,
                ]);

                if(!$restrict) {
                    throw new \InvalidArgumentException("Произошла ошибка при добавлении ограничения");
                }
            } else {
                throw new \InvalidArgumentException("Ограничение уже существует");
            }

            return [
                "status" => true,
                "type" => 0,
                "message" => 'Добавлено новое ограничение',
            ];

        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function getSearchMovie(Request $request)
    {
        try {
            $kpID = $request['kpID'];
            $nameMovie = $request['nameMovie'];
            $alternativeName = $request['alternativeName'];
            $type = $request['type'];
            $year = $request['year'];
            $isSeries = $request['isSeries'];
            $movieLength = $request['movieLength'];
            $seriesLength = $request['seriesLength'];
            $genre = $request['genre'];
            $country = $request['country'];

            $currentPage = $request->input('currentPage', 1);

            $typeID = null;
            $genreID = null;
            $countryID = null;

            if($type) {
                $typeQuery = Type::where('name', $type)->first();

                if($typeQuery) {
                    $typeID = $typeQuery->id;
                } else {
                    throw new \InvalidArgumentException("Такого типа не существует");
                }
            }

            if($genre) {
                $genreQuery = Genre::where('name', $genre)->first();

                if($genreQuery) {
                    $genreID = $genreQuery->id;
                } else {
                    throw new \InvalidArgumentException("Такого жанра не существует");
                }
            }

            if($country) {
                $countryQuery = Countrie::where('country', $country)->first();

                if($countryQuery) {
                    $countryID = $countryQuery->id;
                } else {
                    throw new \InvalidArgumentException("Такой страны не существует");
                }
            }

            $query = Movie::query();

            if ($nameMovie) {
                $query->where('name', $nameMovie);
            }

            if ($kpID) {
                $query->where('externalID', $kpID);
            }

            if ($alternativeName) {
                $query->where('alternativeName', $alternativeName);
            }

            if ($typeID) {
                $query->where('type_id', $typeID);
            }

            if ($year) {
                $query->where('year', $year);
            }

            if ($isSeries) {
                $query->where('isSeries', $isSeries);
            }

            if ($movieLength) {
                $query->where('movieLength', $movieLength);
            }

            if ($seriesLength) {
                $query->where('seriesLength', $seriesLength);
            }

            if ($genreID) {
                $query->whereHas('genres', function ($query) use ($genreID) {
                    $query->where('genre_id', $genreID);
                });
            }

            if ($countryID) {
                $query->whereHas('countries', function ($query) use ($countryID) {
                    $query->where('country_id', $countryID);
                });
            }

            if ($query->getQuery()->wheres) {
                $movies = $query->paginate(10, ['*'], 'page', $currentPage);
            } else {
                $movies = collect(); // Пустая коллекция
            }

            if($query){
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => '200 ok',
                    "data" => $movies,
                ];
            } else {
                return [
                    "status" => true,
                    "type" => 0,
                    "message" => '200 ok',
                    "data" => null,
                ];
            }

        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function getRoles() {
        $roles = Role::select('name')->get();

        if ($roles->isNotEmpty()) {
            $res = [
                "status" => true,
                "type" => 0,
                "message" => "200 ok",
                "items" => $roles,
            ];
        } else {
            $res = [
                "status" => false,
                "type" => 1,
                "message" => "Произошла ошибка"
            ];
        }

        return $res;
    }

    public static function takeRole(Request $request) {
        try {
            $email = $request['email'];
            $role = $request['role'];

            $user = User::where('email', $email)->first();

            if(!$user) {
                throw new \InvalidArgumentException("Пользователя с таким email не существует");
            }

            $existedRole = Role::where('name', $role)->first();

            $roleID = null;

            if($existedRole) {
                $roleID = $existedRole->id;
            } else {
                throw new \InvalidArgumentException("Такой роли не существует");
            }

            $user->role_id = $roleID;
            $user->save();

            return [
                "status" => true,
                "type" => 0,
                "message" => 'Роль пользователя обновлена',
            ];

        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function getSuggestions() {
        try {
            $suggestions = AddMovie::all();

            if ($suggestions->isNotEmpty()) {
                $formattedSuggestions = $suggestions->map(function ($suggestion) {
                    $createdAt = Carbon::parse($suggestion->created_at);
                    $suggestion->formatted_created_at = $createdAt->format('d/m/y');
                    return $suggestion;
                });

                $res = [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "items" => $formattedSuggestions,
                ];
            } else {
                $res = [
                    "status" => false,
                    "type" => 1,
                    "message" => "Произошла ошибка"
                ];
            }

            return $res;
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function getCurrentSuggestion(Request $request) {
        try {
            $userID = $request['suggestedUserID'];
            $ticketID = $request['ticketID'];

            $suggestions = AddMovie::where('user_id', $userID)->where('id', $ticketID)->get();

            if ($suggestions->isNotEmpty()) {
                $formattedSuggestions = $suggestions->map(function ($suggestion) {
                    $createdAt = Carbon::parse($suggestion->created_at);
                    $suggestion->formatted_created_at = $createdAt->format('d/m/y');
                    return $suggestion;
                });

                $res = [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "items" => $formattedSuggestions,
                ];
            } else {
                $res = [
                    "status" => false,
                    "type" => 1,
                    "message" => "Произошла ошибка"
                ];
            }

            return $res;
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function postAnswerForAddMovie(Request $request) {
        try {
            $userID = $request['userID'];
            $ticketID = $request['ticketID'];
            $status = $request['status'];

            $suggestions = AddMovie::where('user_id', $userID)->where('id', $ticketID)->first();

            if ($suggestions) {
                $suggestions->status = $status;
                $suggestions->save();

                $res = [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                ];
            } else {
                $res = [
                    "status" => false,
                    "type" => 1,
                    "message" => "Произошла ошибка"
                ];
            }

            return $res;
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }
}
