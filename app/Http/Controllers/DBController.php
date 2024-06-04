<?php

namespace App\Http\Controllers;

use App\Models\ApiPageNumber;
use App\Models\Backdrop;
use App\Models\Countrie;
use App\Models\External_ID;
use App\Models\Fee;
use App\Models\Genre;
use App\Models\Logo;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Poster;
use App\Models\Premiere;
use App\Models\Restrict;
use App\Models\SequelsAndPrequel;
use App\Models\SimilarMovie;
use App\Models\Trailer;
use App\Models\Type;
use App\Models\Vote;
use Elementor\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use ParagonIE\Sodium\Core\Curve25519\Fe;
use Illuminate\Support\Facades\Log;

class DBController extends Controller
{
    public static function getAllMovies() {
        $fields = implode('&selectFields=', [
            'id',
            'externalId',
            'name',
            'alternativeName',
            'description',
            'shortDescription',
            'slogan',
            'type',
            'isSeries',
            'year',
            'releaseYears',
            'rating',
            'ratingMpaa',
            'ageRating',
            'votes',
            'seasonsInfo',
            'budget',
            'movieLength',
            'seriesLength',
            'totalSeriesLength',
            'genres',
            'countries',
            'poster',
            'backdrop',
            'logo',
            'videos',
            'persons',
            'facts',
            'fees',
            'premiere',
            'similarMovies',
            'sequelsAndPrequels',
            'updatedAt',
            'createdAt'
        ]);

        $res = [];

        // Получаем текущую запись из таблицы
        $pageNumber = ApiPageNumber::latest('current_page')->first();

        // Если запись отсутствует, создаем ее
        if (!$pageNumber) {
            $pageNumber = ApiPageNumber::create(['current_page' => 1]);
        }

        // Получаем текущий номер страницы
        $currentPage = $pageNumber->current_page;

        // Отправляем запрос на текущую страницу
        $response = Http::withHeaders([
            'X-API-KEY' => 'JP68WT9-QE9M46M-NST0V1R-Y711FWC',
            'Accept' => 'application/json',
        ])->get("https://api.kinopoisk.dev/v1.4/movie?page={$currentPage}&limit=250&selectFields={$fields}");

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['message']) && $data['message'] === "Вы пытаетесь запросить больше страниц, чем доступно на самом деле!") {
                // Возвращаем сообщение о завершении загрузки
                $res[] = [
                    "status" => true,
                    "message" => "Загрузка завершена, больше страниц нет",
                    "data" => $data['docs'],
                ];
            } else {
                $res[] = [
                    "status" => true,
                    "message" => "Успешный запрос к api",
                    "data" => $data['docs'],
                ];

                $pageNumber->current_page = $currentPage + 1;
                $pageNumber->save();

                $addFilms = new self();

                try {
                    $addFilms->addMoviesToDB($data['docs']);
                } catch (\Exception $e) {
                    $res[] = [
                        "status" => false,
                        "message" => "Ошибка при добавлении фильмов в базу данных: " . $e->getMessage(),
                    ];
                }
            }

        } else {
            $data = $response->json();
            $res[] = [
                "status" => false,
                "message" => $data['message'],
            ];
        }

        return $res;
    }

    public function addMoviesToDB($movies) {
        foreach ($movies as $movie) {
            try {
                // Получаем или создаем связанные записи Restrict, Type, ExternalID
                $restrict = $this->getOrCreateRelatedRecord(Restrict::class, 'restriction', $movie['ageRating']);
                $type = $this->getOrCreateRelatedRecord(Type::class, 'name', $movie['type']);

                $movieData = $this->extractMovieData($movie, $type, $restrict);

                // Создаем запись фильма
                $createdMovie = Movie::create($movieData);

                $this->addGenresToMovie($movie, $createdMovie);
                $this->addCountriesToMovie($movie, $createdMovie);
                $this->addFeesToMovie($movie, $createdMovie);
                $this->addBackdropToMovie($movie, $createdMovie);
                $this->addPosterToMovie($movie, $createdMovie);
                $this->addLogoToMovie($movie, $createdMovie);
                $this->addPremiereToMovie($movie, $createdMovie);
                $this->addPersonsToMovie($movie, $createdMovie);
                $this->addSequelsAndPrequelsToMovie($movie, $createdMovie);
                $this->addSimilarMoviesToMovie($movie, $createdMovie);
                $this->addTrailersToMovie($movie, $createdMovie);
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    private function extractMovieData($movie, $type, $restrict)
    {
        return [
            'apiID' => $movie['id'],
            'externalID' => $movie['externalId']['kpHD'] ?? null,
            'name' => $movie['name'] ?? null,
            'alternativeName' => $movie['alternativeName'] ?? null,
            'description' => $movie['description'] ?? null,
            'shortDescription' => $movie['shortDescription'] ?? null,
            'slogan' => $movie['slogan'] ?? null,
            'year' => $movie['year'] ?? null,
            'isSeries' => $movie['isSeries'] ?? null,
            'ratingKp' => $movie['rating']['kp'] ?? null,
            'ratingMpaa' => $movie['ratingMpaa'] ?? null,
            'votesKp' => $movie['votes']['kp'] ?? null,
            'seasonInfo' => null,
            'budget' => $movie['budget']['value'] ?? null,
            'movieLength' => $movie['movieLength'] ?? null,
            'seriesLength' => $movie['seriesLength'] ?? null,
            'totalSeriesLength' => $movie['totalSeriesLength'] ?? null,
            'updatedAtDate' => $movie['updatedAt'] ?? null,
            'createdAtDate' => $movie['createdAt'] ?? null,
            'type_id' => $type->id,
            'restrict_id' => $restrict->id,
        ];
    }

    private function addGenresToMovie($movie, $createdMovie)
    {
        if (isset($movie['genres'])) {
            foreach ($movie['genres'] as $genre) {
                $genreRecord = $this->getOrCreateRelatedRecord(Genre::class, 'name', $genre['name']);
                $createdMovie->genres()->attach($genreRecord->id);
            }
        }
    }

    private function addCountriesToMovie($movie, $createdMovie)
    {
        if (isset($movie['countries'])) {
            foreach ($movie['countries'] as $country) {
                $countryRecord = $this->getOrCreateRelatedRecord(Countrie::class, 'country', $country['name']);
                $createdMovie->countries()->attach($countryRecord->id);
            }
        }
    }

    private function addFeesToMovie($movie, $createdMovie)
    {
        if (isset($movie['fees'])) {
            foreach ($movie['fees'] as $country => $valueData) {
                $feesValue = isset($valueData['value']) ? $valueData['value'] : null;
                Fee::create([
                    'movie_id' => $createdMovie->id,
                    'country_name' => $country,
                    'fees' => $feesValue,
                ]);
            }
        }
    }

    private function addBackdropToMovie($movie, $createdMovie) {
        if(isset($movie['backdrop'])) {
            Backdrop::create([
                'movie_id' => $createdMovie->id,
                'previewUrl' => $movie['backdrop']['previewUrl'],
                'url' => $movie['backdrop']['url'],
            ]);
        }
    }

    private function addPosterToMovie($movie, $createdMovie) {
        if(isset($movie['poster'])) {
            Poster::create([
                'movie_id' => $createdMovie->id,
                'previewUrl' => $movie['poster']['previewUrl'],
                'url' => $movie['poster']['url'],
            ]);
        }
    }

    private function addLogoToMovie($movie, $createdMovie) {
        if(isset($movie['logo'])) {
            Logo::create([
                'movie_id' => $createdMovie->id,
                'url' => $movie['logo']['url'],
            ]);
        }
    }

    private function addPremiereToMovie($movie, $createdMovie) {
        $premiereData = [];

        if (isset($movie['premiere']['russia'])) {
            $premiereData['russia'] = $movie['premiere']['russia'];
        }

        if (isset($movie['premiere']['dvd'])) {
            $premiereData['dvd'] = $movie['premiere']['dvd'];
        }

        if (isset($movie['premiere']['world'])) {
            $premiereData['world'] = $movie['premiere']['world'];
        }

        Premiere::create([
            'movie_id' => $createdMovie->id,
        ], $premiereData);
    }

    private function addPersonsToMovie($movie, $createdMovie) {
        if(isset($movie['persons'])) {
            foreach ($movie['persons'] as $person) {
                Person::create([
                    'movie_id' => $createdMovie->id,
                    'name' => $person['name'],
                    'description' => $person['description'],
                    'enName' => $person['enName'],
                    'enProfession' => $person['enProfession'],
                    'profession' => $person['profession'],
                    'photo' => $person['photo'],
                ]);
            }
        }
    }

    private function addSequelsAndPrequelsToMovie($movie, $createdMovie) {
        if(isset($movie['sequelsAndPrequels'])) {
            foreach ($movie['sequelsAndPrequels'] as $sequelsAndPrequel) {
                $spType = $this->getOrCreateRelatedRecord(Type::class, 'name', $sequelsAndPrequel['type']);
                SequelsAndPrequel::create([
                    'movie_id' => $createdMovie->id,
                    'alternativeName' => $sequelsAndPrequel['alternativeName'] ?? null,
                    'enName' => $sequelsAndPrequel['enName'] ?? null,
                    'apiID' => $sequelsAndPrequel['id'] ?? null,
                    'name' => $sequelsAndPrequel['name'] ?? null,
                    'year' => $sequelsAndPrequel['year'] ?? null,
                    'type_id' => $spType->id,
                ]);
            }
        }
    }

    private function addSimilarMoviesToMovie($movie, $createdMovie) {
        if(isset($movie['similarMovies'])) {
            foreach ($movie['similarMovies'] as $similarMovie) {
                $smType = $this->getOrCreateRelatedRecord(Type::class, 'name', $similarMovie['type']);
                SimilarMovie::create([
                    'movie_id' => $createdMovie->id,
                    'alternativeName' => $similarMovie['alternativeName'] ?? null,
                    'enName' => $similarMovie['enName'] ?? null,
                    'apiID' => $similarMovie['id'] ?? null,
                    'name' => $similarMovie['name'] ?? null,
                    'year' => $similarMovie['year'] ?? null,
                    'type_id' => $smType->id,
                ]);
            }
        }
    }

    private function addTrailersToMovie($movie, $createdMovie) {
        if(isset($movie['videos']['trailers'])) {
            foreach ($movie['videos']['trailers'] as $trailer) {
                Trailer::create([
                    'movie_id' => $createdMovie->id,
                    'url' => $trailer['url'],
                ]);
            }
        }
    }


    public function getOrCreateRelatedRecord($modelClass, $fieldName, $fieldValue) {
        // Ищем запись по полю $fieldName и значению $fieldValue
        $record = $modelClass::where($fieldName, $fieldValue)->first();

        // Если запись не найдена, создаем новую
        if (!$record) {
            $record = $modelClass::create([
                $fieldName => $fieldValue,
            ]);
        }

        return $record;
    }

    //types
    public function updateMovieTypes() {
        try {
            $typesResponse = Http::withHeaders([
                'X-API-KEY' => 'JP68WT9-QE9M46M-NST0V1R-Y711FWC',
                'Accept' => 'application/json',
            ])->get("https://api.kinopoisk.dev/v1/movie/possible-values-by-field?field=type");

            if ($typesResponse->successful()) {
                $typesData = $typesResponse->json();
                $this->addTypesToDB($typesData);
            } else {
                throw new \Exception("Ошибка при получении типов фильмов: " . $typesResponse->status());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function addTypesToDB($types) {
        foreach ($types as $type) {
            $existingType = Type::where('name', $type['name'])->first();

            // Если тип уже существует, пропускаем текущую итерацию
            if ($existingType) {
                continue;
            }

            Type::create([
                'name' => $type['name'],
            ]);
        }
    }

    //countries
    public function updateMovieCountries() {
        try {
            $countriesResponse = Http::withHeaders([
                'X-API-KEY' => 'JP68WT9-QE9M46M-NST0V1R-Y711FWC',
                'Accept' => 'application/json',
            ])->get("https://api.kinopoisk.dev/v1/movie/possible-values-by-field?field=countries.name");

            if ($countriesResponse->successful()) {
                $countriesData = $countriesResponse->json();
                $this->addCountriesToDB($countriesData);
            } else {
                throw new \Exception("Ошибка при получении стран фильмов: " . $countriesResponse->status());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function addCountriesToDB($countries) {
        foreach ($countries as $country) {
            $existingCountry = Countrie::where('country', $country['name'])->first();

            // Если страна уже существует, пропускаем текущую итерацию
            if ($existingCountry) {
                continue;
            }

            Countrie::create([
                'country' => $country['name'],
            ]);
        }
    }


    //genres
    public function updateMovieGenres() {
        try {
            $genresResponse = Http::withHeaders([
                'X-API-KEY' => 'JP68WT9-QE9M46M-NST0V1R-Y711FWC',
                'Accept' => 'application/json',
            ])->get("https://api.kinopoisk.dev/v1/movie/possible-values-by-field?field=genres.name");

            if ($genresResponse->successful()) {
                $genresData = $genresResponse->json();
                $this->addGenresToDB($genresData);
            } else {
                throw new \Exception("Ошибка при получении жанров фильмов: " . $genresResponse->status());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function addGenresToDB($genres) {
        foreach ($genres as $genre) {
            $existingGenre = Genre::where('name', $genre['name'])->first();

            // Если жанр уже существует, пропускаем текущую итерацию
            if ($existingGenre) {
                continue;
            }

            Genre::create([
                'name' => $genre['name'],
            ]);
        }
    }
}
