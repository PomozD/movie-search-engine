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
        Schema::table('sequels_and_prequels', function (Blueprint $table) {
            $table->foreign('movie_id', 'sequels_and_prequel_movie_fk')->on('movies')->references('id');
            $table->foreign('type_id', 'sequels_and_prequel_type_fk')->on('types')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sequels_and_prequels', function (Blueprint $table) {
            //
        });
    }
};
