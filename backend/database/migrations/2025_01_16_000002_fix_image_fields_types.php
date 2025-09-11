<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite ne supporte pas ALTER COLUMN, donc on va recréer les colonnes
        Schema::table('participants', function (Blueprint $table) {
            // Supprimer les colonnes problématiques
            $table->dropColumn(['photo_path', 'cin_path']);
        });
        
        // Les recréer avec le bon type
        Schema::table('participants', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('passport_path');
            $table->string('cin_path')->nullable()->after('photo_path');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'cin_path']);
        });
        
        Schema::table('participants', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('passport_path');
            $table->string('cin_path')->nullable()->after('photo_path');
        });
    }
};
