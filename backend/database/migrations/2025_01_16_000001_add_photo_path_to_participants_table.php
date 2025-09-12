<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Ajouter le champ photo_path s'il n'existe pas déjà
            if (!Schema::hasColumn('participants', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('cin_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (Schema::hasColumn('participants', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
        });
    }
};
