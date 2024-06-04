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
        Schema::table('movies', function (Blueprint $table) {
            $table->foreign('type_id', 'movie_type_fk')->on('types')->references('id');
            $table->foreign('restrict_id', 'movie_restrict_fk')->on('restricts')->references('id');
            $table->foreign('external_id', 'movie_external_id_fk')->on('external__i_d_s')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            //
        });
    }
};
