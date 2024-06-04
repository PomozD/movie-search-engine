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
        Schema::table('add_movies', function (Blueprint $table) {
            $table->dropForeign('add_movie_external_id_fk'); // Удалить внешний ключ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_movies', function (Blueprint $table) {
            //
        });
    }
};
