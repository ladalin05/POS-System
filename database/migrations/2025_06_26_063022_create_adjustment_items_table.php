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
        Schema::create('adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->integer('adjustment_id')->index();
            $table->integer('product_id');
            $table->integer('branch_id')->nullable();
            $table->integer('option_id')->nullable();
            $table->double('qoh')->nullable();
            $table->double('new_qoh')->nullable();
            $table->double('quantity')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('type', 20)->nullable();
            $table->integer('product_unit_id')->default(0);
            $table->string('product_unit_code')->default('0');
            $table->double('unit_quantity')->default(0);
            $table->date('expiry')->nullable();
            $table->double('real_unit_cost')->nullable();
            $table->timestamps(); // optional if you want created_at / updated_at
            $table->softDeletes(); // optional if you want soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_items');
    }
};
