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
        Schema::create('point_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('total_weight')->nullable();
            $table->unsignedInteger('pass_mark')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_questions');
    }
};
