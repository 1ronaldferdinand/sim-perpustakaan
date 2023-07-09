<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Writer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer', function (Blueprint $table) {
            $table->uuid('writer_id')->primary();
            $table->string('writer_name');
            $table->string('writer_phone')->nullable();
            $table->string('writer_email')->nullable();
            $table->text('writer_address')->nullable();
            $table->text('writer_image')->nullable();
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('writer');
    }
}
