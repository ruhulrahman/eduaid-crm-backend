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
        Schema::create('lead_dependent_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->string('name')->nullable();
            $table->enum('type', ['Child', 'Spouse', 'Sibling', 'Parents'])->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('mobile_country_id')->nullable();
            $table->string('mobile')->nullable();
            $table->string('alt_mobile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_dependent_info');
    }
};
