<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adjustments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->string('reference_no', 55);
            $table->unsignedBigInteger('warehouse_id');
            $table->text('note')->nullable();
            $table->string('attachment', 55)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('count_id')->nullable();
            $table->unsignedBigInteger('biller_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('status', 100)->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adjustments');
    }
};

