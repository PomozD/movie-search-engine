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
        Schema::table('film_countries', function (Blueprint $table) {
            $table->foreign('movie_id', 'film_countrie_movie_fk')->on('movies')->references('id');
            $table->foreign('country_id', 'film_countries_country_fk')->on('countries')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('film_countries', function (Blueprint $table) {
            //
        });
    }
};
