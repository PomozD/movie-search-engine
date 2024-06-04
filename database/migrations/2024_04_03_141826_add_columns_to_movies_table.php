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
            $table->integer('apiID');
            $table->integer('externalID');
            $table->string('name');
            $table->string('alternativeName');
            $table->string('description');
            $table->string('shortDescription');
            $table->string('slogan');
            $table->string('year');
            $table->boolean('isSeries');
            $table->string('ratingKp');
            $table->string('ratingMpaa');
            $table->integer('votesKp');
            $table->string('seasonInfo');
            $table->integer('budget');
            $table->integer('movieLength');
            $table->integer('seriesLength');
            $table->integer('totalSeriesLength');
            $table->date('updatedAtDate');
            $table->date('createdAtDate');

            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('restrict_id');
            $table->unsignedBigInteger('external_id');
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
