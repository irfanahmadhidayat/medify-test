<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_item_master_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_item_id');
            $table->unsignedBigInteger('master_item_id');
            $table->timestamps();

            $table->foreign('kategori_item_id')->references('id')->on('kategori_items')->cascadeOnDelete();
            $table->foreign('master_item_id')->references('id')->on('master_items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_item_master_item');
    }
};