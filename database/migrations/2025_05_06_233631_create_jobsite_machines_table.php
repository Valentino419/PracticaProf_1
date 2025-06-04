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
        Schema::create('jobsite_machines', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('machine_id')->constrained()->onDelete('cascade');
            $table->foreignId('jobsite_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('kilometers')->nullable();
            $table->string('conclusion_reasons')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobsite_machines');
    }
};
