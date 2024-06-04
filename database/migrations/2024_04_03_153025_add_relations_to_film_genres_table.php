<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('film_genres', function (Blueprint $table) {
            $table->foreign('movie_id', 'film_genre_movie_fk')->on('movies')->references('id');
            $table->foreign('genre_id', 'film_genre_genre_fk')->on('genres')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('film_genres', function (Blueprint $table) {
            //
        });
    }
};
