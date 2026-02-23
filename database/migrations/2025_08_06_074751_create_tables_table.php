<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // e.g. “Table 1”
            $table->string('code')->unique();     // optional shorthand/code
            $table->integer('warehouse_id')     // which warehouse/room area
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('floor_id')         // which floor/zone
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedInteger('capacity')   // number of seats
                ->default(4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
