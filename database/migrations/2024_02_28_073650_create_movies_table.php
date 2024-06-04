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
        Schema::create('movies', function (Blueprint $table) {
            /*$table->id();
            $table->string('name');
            $table->integer('year');

            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('actor_id');
            $table->unsignedBigInteger('director_id');
            $table->unsignedBigInteger('studio_id');
            $table->unsignedBigInteger('restrict_id');
            $table->timestamps();

            $table->foreign('genre_id', 'movie_genre_fk')->on('genres')->references('id');
            $table->foreign('type_id', 'movie_type_fk')->on('types')->references('id');
            $table->foreign('actor_id', 'movie_actor_fk')->on('actors')->references('id');
            $table->foreign('director_id', 'movie_director_fk')->on('directors')->references('id');
            $table->foreign('studio_id', 'movie_studio_fk')->on('studios')->references('id');
            $table->foreign('restrict_id', 'movie_restrict_fk')->on('restricts')->references('id');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('movies');
    }
};
