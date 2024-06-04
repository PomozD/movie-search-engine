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
        Schema::table('user_actions', function (Blueprint $table) {
            $table->foreign('user_id', 'user_action_user_fk')->on('users')->references('id');
            $table->foreign('action_id', 'user_action_actions_fk')->on('actions')->references('id');
            $table->foreign('movie_id', 'user_action_movie_fk')->on('movies')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_actions', function (Blueprint $table) {
            //
        });
    }
};
