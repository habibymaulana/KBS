<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('assigned_by'); // guru
            $table->unsignedBigInteger('assigned_to'); // siswa
            $table->string('title');
            $table->enum('type', ['tugas', 'kuis'])->default('tugas');
            $table->date('due_date')->nullable();
            $table->enum('status', ['Belum', 'Progres', 'Selesai'])->default('Belum');
            $table->unsignedInteger('score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('assigned_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_to')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

