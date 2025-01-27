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
        Schema::create('lead_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('dependent_id')->nullable()->comment('table lead_dependent_info');
            // $table->json('social_contacts')->nullable();
            // $table->tinyInteger('social_contact_show')->default(1);
            $table->string('contact_time')->nullable();
            $table->longText('contact_preference')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('lead_contacts');
    }
};
