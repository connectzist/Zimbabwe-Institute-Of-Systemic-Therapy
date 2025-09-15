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
        Schema::create('advanceddiplomaresults_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); 
            $table->unsignedBigInteger('course_module');
            $table->unsignedBigInteger('module_id'); 
            $table->integer('mark')->unsigned()->default(0);
            $table->boolean('is_published')->default(false);

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_module')->references('id')->on('advanced_diploma_modules')->onDelete('cascade'); 
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advanceddiplomaresults_records');
    }
};
