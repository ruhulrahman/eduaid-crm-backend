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
        Schema::create('question_point_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('question_id')->nullable()->comment("table point_questions");
            $table->string('value')->nullable();
            $table->unsignedInteger('point')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_point_breakdowns');
    }
};
