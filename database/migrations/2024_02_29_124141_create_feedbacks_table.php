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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('feedback');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('movie_id');

            $table->timestamps();

            /*$table->foreign('movie_id', 'feedback_movie_fk')->on('movies')->references('id');
            $table->foreign('user_id', 'feedback_user_fk')->on('users')->references('id');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('feedbacks');
    }
};
