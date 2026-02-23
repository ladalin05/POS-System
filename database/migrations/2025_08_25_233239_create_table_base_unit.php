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
        Schema::create('base_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_unit_id')->constrained('units');
            $table->foreignId('to_unit_id')->constrained('units');
            $table->unsignedBigInteger('numerator');
            // $table->unsignedBigInteger('denominator')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['from_unit_id', 'to_unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_unit');
    }
};
