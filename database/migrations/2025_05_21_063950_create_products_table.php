<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->unique();
            $table->char('name', 255)->nullable();
            $table->integer('unit_id')->nullable();
            $table->double('cost')->default(0);
            $table->double('old_cost')->default(0);
            $table->double('price')->default(0);
            $table->double('alert_quantity')->nullable();
            $table->string('image', 255)->default('no_image.png');
            $table->integer('category_id')->nullable();
            $table->integer('subcategory_id')->nullable();
            $table->string('cf1')->nullable();
            $table->string('cf2')->nullable();
            $table->string('cf3')->nullable();
            $table->string('cf4')->nullable();
            $table->string('cf5')->nullable();
            $table->string('cf6')->nullable();
            $table->double('quantity')->default(0);
            $table->integer('tax_rate')->nullable();
            $table->boolean('track_quantity')->default(1);
            $table->text('details')->nullable();
            $table->integer('warehouse')->nullable();
            $table->string('barcode_symbology', 55)->default('code128');
            $table->string('file', 100)->nullable();
            $table->text('product_details')->nullable();
            $table->boolean('tax_method')->default(0);
            $table->string('type', 55)->default('service');

            // Suppliers
            for ($i = 1; $i <= 5; $i++) {
                $table->integer("supplier{$i}")->nullable();
                $table->double("supplier{$i}price")->nullable();
                $table->string("supplier{$i}_part_no", 50)->nullable();
            }

            $table->boolean('promotion')->default(0);
            $table->double('promo_price')->nullable();
            $table->double('promo_qty')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->integer('customer_id')->nullable();
            $table->integer('sale_unit')->nullable();
            $table->integer('purchase_unit')->nullable();
            $table->integer('brand')->nullable();
            $table->integer('model')->nullable();
            $table->integer('adjustment_qty')->nullable();
            $table->boolean('rate')->default(0);
            $table->boolean('manual_product')->default(0);
            $table->tinyInteger('accounting_method')->default(2);
            $table->boolean('seperate_qty')->default(0);

            // Dimensions
            $table->double('p_length')->default(0);
            $table->double('p_width')->default(0)->nullable();
            $table->double('p_height')->default(0)->nullable();
            $table->double('p_weight')->default(0)->nullable();

            $table->double('currency_rate')->default(1);
            $table->string('currency_code', 10)->default('USD');
            $table->double('product_additional')->default(0);
            $table->boolean('inactive')->default(0);
            $table->boolean('stregth')->default(0);

            // Market info
            $table->string('market_code1')->nullable();
            $table->string('market_name1')->nullable();
            $table->string('market_code2')->nullable();
            $table->string('market_name2')->nullable();
            $table->string('market_code3')->nullable();
            $table->string('market_name3')->nullable();
            $table->softDeletes();
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
