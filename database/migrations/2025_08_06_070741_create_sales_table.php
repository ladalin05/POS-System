<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // basic info
            $table->dateTime('date')->nullable();
            $table->string('reference_no')->nullable();      // Reference No

            // relations
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('biller_id')->nullable();   // Salesman
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();

            // monetary fields
            $table->decimal('total', 14, 2)->default(0);          // Total (items sum)
            $table->decimal('tax', 14, 2)->default(0);            // Tax
            $table->decimal('returned', 14, 2)->default(0);       // Returned amount (if any)
            $table->decimal('discount', 14, 2)->default(0);       // Discount
            $table->decimal('shipping', 14, 2)->default(0);       // Shipping if used
            $table->decimal('grand_total', 14, 2)->default(0);    // Grand total (final)
            $table->decimal('paid', 14, 2)->default(0);           // Amount paid by customer
            $table->decimal('balance', 14, 2)->default(0);        // Remaining balance
            $table->decimal('return_amount', 14, 2)->default(0);  // Change returned to customer

            // statuses & metadata
            $table->string('delivery_status', 50)->default('pending');  // e.g., Pending/Delivered
            $table->string('payment_status', 50)->default('pending');   // e.g., Pending/Completed
            $table->string('status', 50)->default('completed');        // order status if you keep it

            // optional notes / created by
            $table->text('note')->nullable();
            $table->string('created_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
