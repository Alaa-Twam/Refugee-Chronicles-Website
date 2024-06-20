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
        Schema::create('chronicles', function (Blueprint $table) {
            $table->id();
            $table->string('city_id');
            $table->string('title');
            $table->string('youtube_link');
            $table->text('description');
            $table->string('lat');
            $table->string('lng');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chronicles');
    }
};
