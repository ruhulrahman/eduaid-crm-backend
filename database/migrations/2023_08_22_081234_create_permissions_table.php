<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->enum('type', ['Page', 'Feature']);
            $table->string('name')->index();
            $table->string('code')->nullable()->index();
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
        Schema::dropIfExists('permissions');
    }
}
