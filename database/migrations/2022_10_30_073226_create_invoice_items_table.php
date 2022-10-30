<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("product_id")->nullable();
            $table->foreign("product_id")->references("id")->on("products")->nullOnDelete();

            $table->unsignedBigInteger("invoice_id");
            $table->foreign("invoice_id")->references("id")->on("invoices")->cascadeOnDelete();

            $table->float("quantity");
            $table->unsignedInteger("amount");
            $table->unsignedInteger("discount_amount");
            $table->unsignedInteger("after_discount_amount");
            $table->unsignedInteger("tax_amount");
            $table->unsignedInteger("total_due");

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
        Schema::dropIfExists('invoice_items');
    }
};
