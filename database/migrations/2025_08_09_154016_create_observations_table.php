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
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->integer('people_observed')->default(0);
            $table->dateTime('observed_at');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('date_in')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('program_register_id')->constrained('program_registers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('critical_risk_id')->nullable()->constrained('critical_risks')->nullOnDelete();
            $table->foreignId('turn_id')->nullable()->constrained('turns')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
