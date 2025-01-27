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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_url')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nick_name')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->dateTime('dob')->nullable();
            $table->dateTime('enlistment_date')->nullable();
            $table->tinyInteger('is_married')->nullable();
            $table->unsignedInteger('mobile_country_id')->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('alternative_mobile', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('other_email')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('per_city')->nullable();
            $table->string('pre_city')->nullable();
            $table->string('per_post_code')->nullable();
            $table->string('pre_post_code')->nullable();
            $table->unsignedInteger('supervisor_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedInteger('active')->default(1);
            $table->unsignedInteger('source')->nullable();
            $table->tinyInteger('is_client')->default(0);
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
        Schema::dropIfExists('leads');
    }
};
