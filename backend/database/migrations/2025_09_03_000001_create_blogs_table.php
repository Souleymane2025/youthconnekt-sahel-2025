<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Avoid trying to create the table if it already exists (prevents migrator errors when DB already seeded)
        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('category')->nullable();
                $table->text('content');
                $table->string('excerpt', 300)->nullable();
                $table->string('status', 40)->default('draft');
                $table->string('author_name', 120)->default('Admin');
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->index('status');
                $table->index('published_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
