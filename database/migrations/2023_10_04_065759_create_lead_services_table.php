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
        Schema::create('lead_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('service_status_id')->default(0);
            $table->decimal('total_score', 10, 2)->default(0.00);
            $table->date('point_entry_date')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_services');
    }
};
