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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('capital')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('country_code', 3)->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('currency_sub_unit')->nullable();
            $table->string('currency_symbol', 3)->nullable();
            $table->unsignedInteger('currency_decimals')->nullable();
            $table->string('full_name')->nullable();
            $table->string('iso_3166_2', 2)->nullable();
            $table->string('iso_3166_3', 3)->nullable();
            $table->string('name')->nullable();
            $table->string('region_code', 3)->nullable();
            $table->string('sub_region_code', 3)->nullable();
            $table->tinyInteger('eea')->nullable();
            $table->string('calling_code', 3)->nullable();
            $table->string('flag', 6)->nullable();
            $table->unsignedInteger('status')->nullable();
            $table->unsignedInteger('status_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
