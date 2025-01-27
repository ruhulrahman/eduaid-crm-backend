<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model', 255);
            $table->unsignedInteger('model_id');
            $table->unsignedInteger('log_type_id');
            $table->string('subject', 255);
            $table->text('description');
            $table->enum('action', ['Create','Update','Delete','Others','General']);
            $table->longText('data');
            $table->unsignedInteger('creator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
