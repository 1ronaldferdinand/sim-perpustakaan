<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Borrow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow', function (Blueprint $table) {
            $table->uuid('borrow_id')->primary();
            $table->string('book_id');
            $table->string('member_id');
            $table->string('borrow_amount');
            $table->timestamp('borrow_start');
            $table->timestamp('borrow_end');
            $table->integer('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('book_id')->references('book_id')->on('book');
            $table->foreign('member_id')->references('member_id')->on('member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrow');
    }
}
