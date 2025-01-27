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
        Schema::create('lead_socials', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('dependent_id')->nullable()->comment('table lead_dependent_info');
            $table->unsignedInteger('social_id')->nullable();
            $table->string('social_url')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_socials');
    }
};
