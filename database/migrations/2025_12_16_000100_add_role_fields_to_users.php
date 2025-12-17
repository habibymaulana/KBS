<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['siswa', 'guru'])->default('siswa')->after('email');
            }

            if (! Schema::hasColumn('users', 'grade_level')) {
                $table->string('grade_level', 50)->nullable()->after('role');
            }

            if (! Schema::hasColumn('users', 'subject_focus')) {
                $table->string('subject_focus', 100)->nullable()->after('grade_level');
            }

            if (! Schema::hasColumn('users', 'bio')) {
                $table->string('bio', 500)->nullable()->after('subject_focus');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'grade_level', 'subject_focus', 'bio']);
        });
    }
};

