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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name');
            $table->string('surname');
            $table->string('email', 200)->unique();
            $table->string('password');
            $table->boolean('admin')->default(false);
            $table->boolean('display_sales')->default(false);
            $table->string('orcid')->nullable();
            $table->string('twitter')->nullable();
            $table->text('repositories')->nullable();
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
        Schema::dropIfExists('user');
    }
}
