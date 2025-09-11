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
            $table->string('type')->nullable();
            $table->string('organization')->nullable();
            $table->string('occupation')->nullable();
            $table->json('interests')->nullable();
            $table->unsignedSmallInteger('experience')->nullable(); // years of experience
            $table->text('motivation')->nullable();
            $table->string('status', 40)->default('pending');
            $table->timestamps();
            $table->index(['status','type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
