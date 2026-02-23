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
        Schema::create('expense_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('expense_id');
            $table->integer('expense_category_id');
            $table->string('expense_name'); 
            $table->string('expense_code', 50)->nullable();
            $table->string('description')->nullable();
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('quantity', 15, 3)->default(1);
            $table->decimal('subtotal', 15, 2)->default(0); 
            $table->unsignedInteger('line_no')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};
