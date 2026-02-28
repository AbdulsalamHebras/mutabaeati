<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('national_id')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->onDelete('set null');
            $table->enum('duration', ['عام', 'عامين'])->default('عام');
            $table->string('section')->nullable(); // الشعبة
            $table->foreignId('university_id')->nullable()->constrained('universities')->onDelete('set null');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('set null');
            $table->string('academic_id')->nullable();
            $table->string('platform_password')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['نشط', 'مقيد'])->default('نشط');
            $table->foreignId('muhdir_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
