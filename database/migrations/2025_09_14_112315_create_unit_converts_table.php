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
        Schema::create('unit_converts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_from_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('unit_to_id')->constrained('units')->onDelete('cascade');
            $table->decimal('numerator', 16, 6)->default(1); // conversion factor e.g. 24.000000
            $table->string('operator', 2)->default('*'); // '*' or '/'
            $table->boolean('is_active')->default(true);
            $table->string('name')->nullable(); // human readable like "Case → Can"
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_converts');
    }
};
