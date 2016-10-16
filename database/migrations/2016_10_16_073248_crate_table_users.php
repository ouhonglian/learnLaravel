<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users',function (Blueprint $table){
            $table->increments('id');//创建z主键且自增
            $table->timestamps();//创建时间和更新时间
            $table->string('username',32)->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('pic_url')->nullable();
            $table->text('intro')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
