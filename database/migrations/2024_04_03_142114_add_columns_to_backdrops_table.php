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
        Schema::table('backdrops', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id');
            $table->string('previewUrl');
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('backdrops', function (Blueprint $table) {
            //
        });
    }
};
