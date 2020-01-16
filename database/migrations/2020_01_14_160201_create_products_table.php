<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('image')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('url');
            $table->string('upc')->nullable();
            $table->string('gtin14')->nullable();
            $table->decimal('price', 13, 2);
            $table->timestamps();

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
