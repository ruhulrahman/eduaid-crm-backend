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
        Schema::create('lead_question_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('question_id')->nullable();
            $table->unsignedInteger('qp_breakdown_id')->nullable()->comment("table question_point_breakdowns");
            $table->unsignedInteger('point')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_question_points');
    }
};
