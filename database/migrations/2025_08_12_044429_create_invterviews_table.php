<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('interview_staff', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('gender', 20);
            $table->date('dob');
            $table->string('birth_place')->nullable();
            $table->string('nationality', 120)->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->string('address');
            $table->string('phone', 50);
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->string('position_applied');
            $table->longText('education')->nullable();
            $table->longText('work_experience')->nullable();
            $table->longText('skills')->nullable();
            $table->longText('languages')->nullable();
            $table->longText('references')->nullable();
            $table->date('interview_date');
            $table->time('interview_time');
            $table->string('interviewer_name')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interview_staff');
    }
};
