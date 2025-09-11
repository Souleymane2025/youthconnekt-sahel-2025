<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
   {
      Schema::create('participants', function (Blueprint $table) {
         $table->id();
         $table->string('first_name');
         $table->string('last_name');
         $table->string('email')->unique();
         $table->string('phone')->nullable();
         $table->string('country');
         $table->string('city');
         $table->string('type')->nullable(); // national / international
         $table->string('status')->default('pending');
         $table->string('organization')->nullable();
         $table->string('occupation')->nullable();
         $table->json('interests')->nullable();
         $table->text('experience')->nullable();
         $table->text('motivation')->nullable();
         $table->timestamps();
      });
   }
   public function down(): void
   {
      Schema::dropIfExists('participants');
   }
};