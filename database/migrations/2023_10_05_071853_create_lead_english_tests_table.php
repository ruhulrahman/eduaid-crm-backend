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
        Schema::create('lead_english_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('dependent_id')->nullable();
            $table->unsignedInteger('language_test_id')->nullable();
            $table->unsignedInteger('level_id')->nullable();
            $table->tinyInteger('is_primary')->default(0);
            $table->date('test_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->string('over_all_result')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_english_tests');
    }
};
