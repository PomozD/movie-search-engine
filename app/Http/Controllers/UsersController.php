<?php

namespace App\Http\Controllers;

use App\Models\AddMovie;
use App\Models\Countrie;
use App\Models\Genre;
use App\Models\Restrict;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class UsersController extends Controller
{
    public static function postUser(Request $request) {
        $nickname = $request['name'];
        $email = $request['email'];
        $password = $request['password'];

        $existingUser = User::where('email', $email)->first();

        $res = [];

        if ($existingUser) {
            $res[] = [
                "status" => false,
                "type" => 1,
                "message" => "Пользователь с таким Email уже зарегистрирован"
            ];

        } else {
            // Хэш пароль
            $hashedPassword = Hash::make($password);

            $role = 2;
            if ($email === 'adminadmin@mail.com' && $password === 'adminadmin') {
                $role = 1;
            }

            // Создаем нового пользователя
            User::create([
                'nickname' => $nickname,
                'email' => $email,
                'password' => $hashedPassword,
                'role_id' => $role,
            ]);

            $res[] = [
                "status" => true,
                "type" => 0,
                "message" => "Регистрация прошла успешно!"
            ];
        }

        return $res;
    }

    public static function redirectGoogle() {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function callbackGoogle(Request $request) {
        try {
            $code = $request['code'];

            // Получаем ответ с токеном доступа
            $tokenResponse = Socialite::driver('google')->getAccessTokenResponse($code);

            // Передаем токен в метод для получения объекта пользователя
            $user = Socialite::driver('google')->userFromToken($tokenResponse['access_token']);

            $existingUser = User::where('email', $user->getEmail())->first();

            $res = [];

            if ($existingUser) {
                if ($existingUser->password == '') {
                    $res[] = [
                        "status" => true,
                        "type" => 0,
                        "message" => "Вход успешен",
                        "user" => $existingUser,
                    ];
                } else {
                    $res[] = [
                        "status" => false,
                        "type" => 1,
                        "message" => "Войдите стандартным способом",
                    ];
                }

            } else {
                // Создаем нового пользователя
                $newUser = User::create([
                    'nickname' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => '',
                    'role_id' => 2,
                ]);

                $res[] = [
                    "status" => true,
                    "type" => 0,
                    "message" => "Вход успешен!",
                    "user"=>$newUser['data']
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

    public static function getUser(Request $request) {

        $email = $request['email'];
        $password = $request['password'];

        $user = User::where('email', $email)->first();

        $res = [];

        if($user && Hash::check($password, $user->password)) {
            $res[] = [
                "status" => true,
                "type" => 0,
                "message" => "Вход успешен!",
                "user"=>$user
            ];

            $_SESSION['user'] = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
            ];

        } else {
            $res[] = [
                "status" => false,
                "type" => 1,
                "message" => "Пользователя с таким Email не существует или неправильный пароль",
            ];
        }

        return $res;
    }

    public static function getUserInfo(Request $request) {
        $id = $request['id'];

        $user = User::select('users.*', 'roles.name as role')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->first();

        if ($user) {
            $res[] = [
                "status" => true,
                "type" => 0,
                "message" => "200 ok",
                "userInfo"=>$user
            ];
        } else {
            $res[] = [
                "status" => false,
                "type" => 1,
                "message" => "Пользователя с таким ID не существует"
            ];
        }

        return $res;
    }

    public static function editProfile(Request $request) {
        $id = $request['userID'];
        $name = $request['name'];
        $email = $request['email'];
        $old_email = $request['old_email'];
        $password = $request['password'];

        $existingUser = User::where('email', $old_email)
            ->where('id', '!=', $id)
            ->exists();

        if ($existingUser) {
            return response()->json([
                "status" => false,
                "type" => 1,
                "message" => "Пользователь с таким Email уже зарегистрирован"
            ]);
        }

        // Обновление данных пользователя
        $user = User::find($id);

        if ($user) {
            if (!empty($name)) {
                $user->nickname = $name;
            }

            if (!empty($email)) {
                $user->email = $email;
            }

            if (!empty($password)) {
                $hashedPassword = Hash::make($password);
                $user->password = $hashedPassword;
            }

            $user->save();

            return response()->json([
                "status" => true,
                "type" => 0,
                "message" => "Данные успешно изменились",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "type" => 1,
                "message" => "Произошла ошибка, данные не были изменены",
            ]);
        }
    }

    public static function suggestMovie(Request $request) {
        try {
            $userMovieSelectedTypeOption = $request['userMovieSelectedTypeOption'] ?? null;
            $userMovieSelectedRestrictOption = $request['userMovieSelectedRestrictOption'] ?? null;
            $userIsSeries = $request['userIsSeries'] ?? null;
            $userYear = $request['userYear'] ?? null;
            $userAlternativeName = $request['userAlternativeName'] ?? null;
            $userBudget = $request['userBudget'] ?? null;
            $userCountry = $request['userCountry'] ?? null;
            $userDescription = $request['userDescription'] ?? null;
            $userMovieLength = $request['userMovieLength'] ?? null;
            $userNameMovie = $request['userNameMovie'] ?? null;
            $userRatingMpaa = $request['userRatingMpaa'] ?? null;
            $userShortDescription = $request['userShortDescription'] ?? null;
            $userSeriesLength = $request['userSeriesLength'] ?? null;
            $userSlogan = $request['userSlogan'] ?? null;
            $userID = $request['userID'] ?? null;

            $typeID = null;
            $genreID = null;
            $countryID = null;
            $restrictID = null;

            if($userMovieSelectedTypeOption) {
                $type = Type::where('name', $userMovieSelectedTypeOption)->first();

                if($type) {
                    $typeID = $type->id;
                } else {
                    throw new \InvalidArgumentException("Такого типа не существует");
                }
            }

            if($userMovieSelectedRestrictOption) {
                $restrict = Restrict::where('restriction', $userMovieSelectedRestrictOption)->first();

                if($restrict) {
                    $restrictID = $restrict->id;
                } else {
                    throw new \InvalidArgumentException("Такого ограничения не существует");
                }
            }


            $addMovie = AddMovie::create([
                'name' => $userNameMovie,
                'alternativeName' => $userAlternativeName,
                'description' => $userDescription,
                'shortDescription' => $userShortDescription,
                'slogan' => $userSlogan,
                'year' => $userYear,
                'isSeries' => $userIsSeries,
                'ratingMpaa' => $userRatingMpaa,
                'budget' => $userBudget,
                'movieLength' => $userMovieLength,
                'seriesLength' => $userSeriesLength,

                'type_id' => $typeID,
                'restrict_id' => $restrictID,
                'user_id' => $userID,

                'status' => 'review',
            ]);

            if(!$addMovie) {
                throw new \Exception("Запись не была создана");
            }

            $addMovie->genres()->attach($genreID);
            $addMovie->countries()->attach($countryID);

            return [
                "status" => true,
                "type" => 0,
                "message" => 'Фильм предложен',
            ];
        } catch (\Exception $e) {
            return [
                "status" => false,
                "type" => 1,
                "message" => $e->getMessage(),
            ];
        }
    }

    public static function getSuggestions(Request $request) {
        try {
            $id = $request['id'];

            $suggestions = AddMovie::where('user_id', $id)->get();

            if ($suggestions) {
                $formattedSuggestions = $suggestions->map(function ($suggestion) {
                    $createdAt = Carbon::parse($suggestion->created_at);
                    $suggestion->formatted_created_at = $createdAt->format('d/m/y');
                    return $suggestion;
                });

                $res[] = [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                    "items"=>$formattedSuggestions
                ];
            } else {
                $res[] = [
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

    public static function deleteSuggestion(Request $request) {
        try {
            $userID = $request['id'];
            $id = $request['tickedID'];

            $suggestions = AddMovie::where('user_id', $userID)->where('id', $id)->first();

            if ($suggestions) {
                $suggestions->delete();

                $res[] = [
                    "status" => true,
                    "type" => 0,
                    "message" => "200 ok",
                ];
            } else {
                $res[] = [
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
