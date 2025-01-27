<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Users'])->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('serial')->nullable();
            $table->unsignedTinyInteger('active')->default(1)->comment('1=active, 0=inactive');
            $table->integer('creator_id')->nullable();
            $table->integer('editor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
