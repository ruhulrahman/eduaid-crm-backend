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
        Schema::create('lead_employment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('dependent_id')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('designation')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('still_working')->default(0);
            $table->string('location')->nullable();
            $table->tinyInteger('is_foreign')->default(0);
            $table->tinyInteger('is_primary')->default(0);
            $table->unsignedInteger('country_id')->nullable();
            $table->string('responsibility')->nullable();
            $table->unsignedInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_employment_histories');
    }
};
