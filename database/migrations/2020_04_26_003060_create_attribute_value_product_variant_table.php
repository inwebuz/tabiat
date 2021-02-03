<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeValueProductVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_value_product_variant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attribute_value_id');
            $table->unsignedBigInteger('product_variant_id');
            $table->timestamps();

            $table->foreign('attribute_value_id', 'av_pv_av_id_foreign')->references('id')->on('attribute_values')->onDelete('cascade');
            $table->foreign('product_variant_id', 'av_pv_pv_id_foreign')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_value_product_variant');
    }
}
