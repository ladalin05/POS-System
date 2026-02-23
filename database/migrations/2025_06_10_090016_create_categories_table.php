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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Equivalent to int(11) AUTO_INCREMENT PRIMARY KEY
            $table->string('code', 55);
            $table->string('name', 55);
            $table->string('image', 55)->nullable();
            $table->string('biller', 255)->nullable();
            $table->string('project', 255)->nullable();
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->boolean('installment')->default(0);
            $table->unsignedInteger('type_id')->nullable();
            $table->string('type', 100)->nullable();
            $table->unsignedInteger('stock_acc')->nullable();
            $table->unsignedInteger('adjustment_acc')->nullable();
            $table->unsignedInteger('usage_acc')->nullable();
            $table->unsignedInteger('cost_acc')->nullable();
            $table->unsignedInteger('convert_acc')->nullable();
            $table->unsignedInteger('discount_acc')->nullable();
            $table->unsignedInteger('sale_acc')->nullable();
            $table->unsignedInteger('expense_acc')->nullable();
            $table->unsignedInteger('pawn_acc')->nullable();
            $table->string('other_name', 55)->nullable();
            $table->string('size', 55)->nullable();
            $table->boolean('inactive')->default(0);
            $table->string('transfer_acc', 255)->nullable();

            $table->softDeletes();
            $table->timestamps(); // Adds created_at and updated_at
            $table->index('id'); // Optional, since it's the primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
