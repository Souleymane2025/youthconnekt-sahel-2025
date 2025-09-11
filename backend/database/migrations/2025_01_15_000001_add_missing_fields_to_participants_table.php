<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Ajouter les champs manquants
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('province')->nullable()->after('country');
            $table->string('handicap')->nullable()->after('motivation');
            $table->string('whatsapp_opt', 10)->nullable()->after('handicap');
            $table->string('passport_path')->nullable()->after('whatsapp_opt');
            $table->string('cin_path')->nullable()->after('passport_path');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp',
                'province', 
                'handicap',
                'whatsapp_opt',
                'passport_path',
                'cin_path'
            ]);
        });
    }
};

