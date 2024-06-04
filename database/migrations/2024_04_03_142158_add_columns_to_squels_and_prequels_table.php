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
            $table->unsignedBigInteger('movie_id');

            $table->string('alternativeName');
            $table->string('enName');
            $table->integer('apiID');
            $table->string('name');
            $table->string('year');

            $table->unsignedBigInteger('type_id');
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
