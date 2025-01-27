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
        Schema::create('lead_english_test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('dependent_id')->nullable();
            $table->unsignedInteger('lead_english_test_id')->nullable();
            $table->unsignedInteger('child_language_test_id')->nullable()->comment("table language_tests");
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_english_test_results');
    }
};
