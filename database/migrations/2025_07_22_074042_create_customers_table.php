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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_group_id')->nullable()->constrained('group_customers')->nullOnDelete();
            $table->integer('price_group_id')->nullable()->constrained('price_groups')->nullOnDelete();
            $table->integer('salesman_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('company');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('email_address')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            $table->integer('credit_day')->nullable();
            $table->decimal('credit_amount', 15, 2)->nullable();
            $table->string('attachment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
