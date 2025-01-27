<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('user_type', ['app_user', 'admin'])->default('app_user');
            $table->integer('role_id')->nullable();
            $table->integer('media_id')->nullable();
            $table->string('social_avatar')->nullable();
            $table->integer('google_id')->nullable();
            $table->integer('facebook_id')->nullable();
            $table->integer('apple_id')->nullable();
            $table->string('device_token')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedTinyInteger('active')->default(1)->comment('1=active, 0=inactive');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
