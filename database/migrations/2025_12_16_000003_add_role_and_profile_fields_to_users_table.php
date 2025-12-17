<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa')->after('email');
            $table->string('grade_level')->nullable()->after('role');
            $table->string('subject_focus')->nullable()->after('grade_level');
            $table->text('bio')->nullable()->after('subject_focus');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'grade_level', 'subject_focus', 'bio']);
        });
    }
};

