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
        Schema::create('lead_education_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('dependent_id')->nullable();
            $table->unsignedInteger('institute_country_id')->nullable();
            $table->string('institute_name')->nullable();
            $table->unsignedInteger('is_foreign_institute')->default(0);
            $table->unsignedInteger('course_level_id')->nullable();
            $table->string('course_name')->nullable();
            $table->unsignedInteger('result_type_id')->nullable();
            $table->string('marks', 50)->nullable();
            $table->string('grade', 10)->nullable();
            $table->string('grade_scale', 10)->nullable();
            $table->unsignedInteger('year_of_passing')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_education_histories');
    }
};
