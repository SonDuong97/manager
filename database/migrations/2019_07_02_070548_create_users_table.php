<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('username', 255);
            $table->string('password', 255);
            $table->string('email', 255);
            $table->string('avatar', 255)->default(url('\images\default-avatar.jpg'));
            $table->text('description')->nullable();
            $table->boolean('notified')->default(0)->comment('cc; 1:true; 0:false');
            $table->string('role', 255)->default('staff');
            $table->integer('manager_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
