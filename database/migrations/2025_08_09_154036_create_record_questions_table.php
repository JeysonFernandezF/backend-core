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
        Schema::create('record_questions', function (Blueprint $table) {
            $table->id();
            $table->text('notes', 500)->nullable();
            $table->boolean('is_safe')->default(true);
            $table->foreignId('observation_id')->constrained('observations')->onDelete('cascade');
            $table->foreignId('form_question_id')->constrained('form_questions')->onDelete('cascade');
            $table->foreignId('conduct_id')->nullable()->constrained('conducts')->nullOnDelete();
            $table->foreignId('barrier_id')->nullable()->constrained('barriers')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_questions');
    }
};
