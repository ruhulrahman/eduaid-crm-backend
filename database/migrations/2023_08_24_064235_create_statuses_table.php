<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('status_group_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('code', 255)->nullable();
            $table->unsignedInteger('serial')->nullable();
            $table->string('color_name', 50)->nullable();
            $table->unsignedTinyInteger('active')->default(1)->comment('1=active, 0=inactive');
            $table->unsignedTinyInteger('editable')->default(1)->comment('1=true, 0=false');
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
        Schema::dropIfExists('statuses');
    }
}
