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
        Schema::table('user_song_queue', function (Blueprint $table) {
            $table->string('status')->default('queued'); // Add a status column with default value 'queued'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_song_queue', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
