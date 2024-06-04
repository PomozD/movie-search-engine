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
        Schema::table('collection_movies', function (Blueprint $table) {
            $table->foreign('collection_id', 'collection_movie_collection_fk')->on('collections')->references('id');
            $table->foreign('movie_id', 'collection_movie_movie_fk')->on('movies')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_movies', function (Blueprint $table) {
            //
        });
    }
};
