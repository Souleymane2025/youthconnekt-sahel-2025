<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
   {
      Schema::create('blogs', function (Blueprint $table) {
         $table->id();
         $table->string('title');
         $table->string('slug')->unique();
         $table->string('category')->nullable();
         $table->string('excerpt')->nullable();
         $table->longText('content');
         $table->string('status')->default('draft');
         $table->string('author_name')->nullable();
         $table->timestamp('published_at')->nullable();
         $table->timestamps();
      });
   }
   public function down(): void
   {
      Schema::dropIfExists('blogs');
   }
};