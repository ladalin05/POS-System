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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->dateTime('date');
            $table->string('reference_no')->nullable();
            $table->string('si_reference_no')->nullable();

            $table->integer('branch_id');
            $table->integer('warehouse_id');
            $table->integer('supplier_id');

            $table->double('order_tax')->default(0);
            $table->double('order_discount')->default(0);
            $table->double('total')->default(0);
            $table->double('grand_total')->default(0);
            $table->string('status', 100)->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('payment_term')->nullable();
            $table->string('attachment')->nullable();
            $table->text('note')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
