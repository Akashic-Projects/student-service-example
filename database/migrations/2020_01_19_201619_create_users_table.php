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
            $table->bigIncrements('id');

            $table->bigInteger('authority_id')->unsigned();
            $table->foreign('authority_id')->references('id')->on('authorities')->onDelete('cascade');

            $table->string('name');
            $table->string('email');
            $table->string('password');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['authority_id']);
        });

        Schema::dropIfExists('users');
    }
}
