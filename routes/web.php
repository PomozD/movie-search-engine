<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::group(['prefix' => 'api/movies', 'as' => 'api.movies.'], function() {
    Route::get("/", [\App\Http\Controllers\DBController::class, "getAllMovies"]);
});*/

Route::group(['prefix' => 'api/admin', 'as' => 'api.admin.'], function() {
    Route::get("/getMoviesTypes", [\App\Http\Controllers\AdminController::class, "getMoviesTypes"]);
    Route::get("/getMoviesRestricts", [\App\Http\Controllers\AdminController::class, "getMoviesRestricts"]);
    Route::get("/getMoviesGenres", [\App\Http\Controllers\AdminController::class, "getMoviesGenres"]);
    Route::get("/getSearchMovie", [\App\Http\Controllers\AdminController::class, "getSearchMovie"]);
    Route::post("/addMovie", [\App\Http\Controllers\AdminController::class, "addMovie"]);
    Route::post("/addCountry", [\App\Http\Controllers\AdminController::class, "addCountry"]);
    Route::post("/addGenre", [\App\Http\Controllers\AdminController::class, "addGenre"]);
    Route::post("/addRestrict", [\App\Http\Controllers\AdminController::class, "addRestrict"]);
    Route::get("/getRoles", [\App\Http\Controllers\AdminController::class, "getRoles"]);
    Route::post("/takeRole", [\App\Http\Controllers\AdminController::class, "takeRole"]);
    Route::get("/getSuggestions", [\App\Http\Controllers\AdminController::class, "getSuggestions"]);
    Route::get("/getCurrentSuggestion", [\App\Http\Controllers\AdminController::class, "getCurrentSuggestion"]);
    Route::post("/postAnswerForAddMovie", [\App\Http\Controllers\AdminController::class, "postAnswerForAddMovie"]);
});


Route::group(['prefix' => 'api/users', 'as' => 'api.users.'], function() {
    Route::post("/", [\App\Http\Controllers\UsersController::class, "postUser"]);
    Route::get("/", [\App\Http\Controllers\UsersController::class, "getUser"]);
    Route::get("/googleAuth", [\App\Http\Controllers\UsersController::class, "redirectGoogle"]);
    Route::get("/googleAuth/callback", [\App\Http\Controllers\UsersController::class, "callbackGoogle"]);
    Route::get("/profile", [\App\Http\Controllers\UsersController::class, "getUserInfo"]);
    Route::post("/profile/edit", [\App\Http\Controllers\UsersController::class, "editProfile"]);
    Route::post("/profile/suggestMovie", [\App\Http\Controllers\UsersController::class, "suggestMovie"]);
    Route::get("/profile/getSuggestions", [\App\Http\Controllers\UsersController::class, "getSuggestions"]);
    Route::get("/profile/deleteSuggestion", [\App\Http\Controllers\UsersController::class, "deleteSuggestion"]);
});

Route::group(['prefix' => 'api/movies', 'as' => 'api.movies.'], function() {
    Route::get("/recommendations", [\App\Http\Controllers\MoviesController::class, "getRecommendationMovies"]);
    Route::get("/recommendations/addRating", [\App\Http\Controllers\MoviesController::class, "calculateRating"]);
    Route::get("/recommendations/getRating", [\App\Http\Controllers\MoviesController::class, "getRating"]);
    Route::get("/recommendations/getMoviesData", [\App\Http\Controllers\MoviesController::class, "getMoviesData"]);

    Route::get("/collections/addCollection", [\App\Http\Controllers\MoviesController::class, "addCollection"]);
    Route::get("/collections/getCollections", [\App\Http\Controllers\MoviesController::class, "getCollections"]);
    Route::get("/collections/getCollectionsMovie", [\App\Http\Controllers\MoviesController::class, "getCollectionsMovie"]);
    Route::get("/collections/searchMovies", [\App\Http\Controllers\MoviesController::class, "searchMovies"]);
    Route::post("/collections/addMovieToCollection", [\App\Http\Controllers\MoviesController::class, "addMovieToCollection"]);
    Route::post("/collections/deleteMovieFromCollection", [\App\Http\Controllers\MoviesController::class, "deleteMovieFromCollection"]);
    Route::post("/collections/deleteCollectionFromCollections", [\App\Http\Controllers\MoviesController::class, "deleteCollectionFromCollections"]);

    Route::get("/movie/getMovie", [\App\Http\Controllers\MoviesController::class, "getMovie"]);
});

Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*');
//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
