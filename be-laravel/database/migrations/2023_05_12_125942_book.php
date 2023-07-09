<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Book extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->uuid('book_id')->primary();
            $table->string('isbn')->default('0000000000');
            $table->string('writer_id');
            $table->string('publisher_id');
            $table->string('book_name');
            $table->string('book_type');
            $table->integer('book_stock')->default(0);
            $table->integer('book_price')->default(0);
            $table->integer('book_size')->nullable();
            $table->string('book_publish_city');
            $table->timestamp('book_publish_date');
            $table->timestamp('book_print_date');
            $table->integer('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('writer_id')->references('writer_id')->on('writer');
            $table->foreign('publisher_id')->references('publisher_id')->on('publisher');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book');
    }
}
