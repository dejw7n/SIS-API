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
        Schema::create('mapping_post_files', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('post_id')
                ->constrained('posts')
                ->onDelete('cascade');
            $table
                ->foreignId('file_id')
                ->constrained('files')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapping_post_files');
    }
};
