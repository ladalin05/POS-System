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
        Schema::create('customer_deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('code')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('name')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('address')->nullable();
            $table->string('customer')->nullable();
            $table->double('amount')->default(0);
            $table->string('paid_by')->nullable();
            $table->string('attachment')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_deposits');
    }
};
