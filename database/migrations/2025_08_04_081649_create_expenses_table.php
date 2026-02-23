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
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->string('reference_no', 50)->nullable();
            $table->integer('branch_id');
            $table->integer('warehouse_id');
            $table->integer('paid_by');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('attachment')->nullable();
            $table->text('note')->nullable();
            $table->double('grand_total')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
