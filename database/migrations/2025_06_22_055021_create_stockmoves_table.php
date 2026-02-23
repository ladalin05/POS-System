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
        Schema::create('stockmoves', function (Blueprint $table) {
            $table->id();
            $table->string('transaction', 50)->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_type', 20)->nullable();
            $table->string('product_code', 100)->nullable();
            $table->dateTime('date')->nullable();
            $table->double('quantity')->default(0);
            $table->double('unit_quantity')->default(1);
            $table->string('unit_code', 50)->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('option_id')->default(0);
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->date('expiry')->nullable();
            $table->double('real_unit_cost')->default(0);
            $table->string('serial_no', 100)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('actual_date')->useCurrent();

            $table->index('product_id');
            $table->index('warehouse_id');
            $table->index('transaction');
            $table->index('transaction_id');

            $table->softDeletes();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockmoves');
    }
};
