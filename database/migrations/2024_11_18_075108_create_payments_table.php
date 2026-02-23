<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tran_id', 50)->nullable()->unique();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->enum('account', ['invoice', 'student', 'parent'])->default('invoice');
            $table->unsignedInteger('tran_no')->nullable();
            $table->float('amount', 16, 4)->nullable();
            $table->float('exchange_rate', 16, 4)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('status', ['pending', 'declined', 'completed'])->default('pending');
            $table->longText('remark')->nullable();
            $table->longText('partner_req')->nullable();
            $table->longText('partner_res')->nullable();
            $table->longText('req')->nullable();
            $table->longText('res')->nullable();
            $table->longText('callback')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
