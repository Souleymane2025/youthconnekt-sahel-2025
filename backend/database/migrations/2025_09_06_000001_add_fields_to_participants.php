<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('participants', 'province')) {
                $table->string('province')->nullable()->after('city');
            }
            if (!Schema::hasColumn('participants', 'handicap')) {
                $table->string('handicap')->nullable()->after('transport');
            }
            if (!Schema::hasColumn('participants', 'whatsapp_opt')) {
                $table->string('whatsapp_opt')->nullable()->after('handicap');
            }
            if (!Schema::hasColumn('participants', 'passport_path')) {
                $table->string('passport_path')->nullable()->after('motivation');
            }
            if (!Schema::hasColumn('participants', 'cin_path')) {
                $table->string('cin_path')->nullable()->after('passport_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            foreach (['whatsapp','province','handicap','whatsapp_opt','passport_path','cin_path'] as $col) {
                if (Schema::hasColumn('participants', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
