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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('share');

            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            /*$table->foreign('movie_id', 'collection_movie_fk')->on('movies')->references('id');
            $table->foreign('user_id', 'collection_user_fk')->on('users')->references('id');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('collections');
    }
};
