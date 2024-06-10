<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_song_queue', function (Blueprint $table) {
            $table->id('queue_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('song_id');
            $table->string('status')->default('queued');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('song_id')->references('song_id')->on('songs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_song_queue');
    }
};
