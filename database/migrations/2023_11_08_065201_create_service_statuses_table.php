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
        Schema::create('service_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('color_code')->nullable();
            $table->unsignedInteger('serial')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('creator_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_statuses');
    }
};
