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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('name_kh', 255)->nullable();
            $table->string('phone', 100);
            $table->string('phone_kh', 100)->nullable();
            $table->string('address', 1000);
            $table->string('address_kh', 1000)->nullable();
            $table->string('city', 120);
            $table->string('city_kh', 120)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('country_kh', 120)->nullable();
            $table->string('vat_number', 120)->nullable();
            $table->string('vat_number_kh', 120)->nullable();
            $table->string('email', 255);
            $table->string('prefix', 50)->nullable();
            $table->enum('default_cash', ['Cash', 'Card', 'Bank'])->default('Cash');
            $table->integer('working_day')->default(0);
            $table->text('invoice_footer')->nullable();
            $table->string('logo', 500)->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
