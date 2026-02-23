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
        Schema::create('suspend_items', function (Blueprint $table) {
            $table->id();
            $table->integer('suspend_id');
            $table->integer('product_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->integer('unit_id');
            $table->string('code');
            $table->decimal('subtotal', 10, 2);
            $table->integer('salesman_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspend_items');
    }
};
