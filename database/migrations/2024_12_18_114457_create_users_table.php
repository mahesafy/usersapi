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
            $table->id(); // id (Primary Key, Auto Increment)
            $table->string('email', 255)->unique(); // email (Unique, Not Null)
            $table->string('password', 255); // password (Not Null)
            $table->string('name', 255); // name (Not Null)
            $table->boolean('active')->default(true); // active (Default: true)
            $table->timestamps(); // created_at and updated_at (Default: Current Timestamp)
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