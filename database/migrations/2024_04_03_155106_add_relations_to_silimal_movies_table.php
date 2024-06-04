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
        Schema::table('similar_movies', function (Blueprint $table) {
            $table->foreign('movie_id', 'similar_movie_movie_fk')->on('movies')->references('id');
            $table->foreign('type_id', 'similar_movie_type_fk')->on('types')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('silimal_movies', function (Blueprint $table) {
            //
        });
    }
};
