<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->integer('sale_id');
            $table->string('reference_no')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('amount_usd', 15, 2)->default(0);
            $table->decimal('rate_usd', 18, 6)->default(1);
            $table->decimal('amount_khr', 18, 2)->default(0);
            $table->decimal('rate_khr', 18, 4)->default(4000);
            $table->string('paying_by')->nullable();
            $table->string('allow_overpayment')->nullable();

            $table->string('attachment')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
