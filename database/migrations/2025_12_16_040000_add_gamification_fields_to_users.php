<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'points')) {
                $table->unsignedInteger('points')->default(0)->after('bio');
            }
            if (! Schema::hasColumn('users', 'level')) {
                $table->string('level', 20)->default('Bronze')->after('points');
            }
            if (! Schema::hasColumn('users', 'streak_days')) {
                $table->unsignedInteger('streak_days')->default(0)->after('level');
            }
            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('streak_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }
            if (Schema::hasColumn('users', 'streak_days')) {
                $table->dropColumn('streak_days');
            }
            if (Schema::hasColumn('users', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('users', 'points')) {
                $table->dropColumn('points');
            }
        });
    }
};


