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
        Schema::create('diploma_final_module', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('student_id');
        $table->unsignedBigInteger('module_id');

        $table->integer('exam_mark')->nullable();
        $table->integer('practical_mark')->nullable();
        $table->integer('research_mark')->unsigned()->default(0);
        $table->boolean('is_published')->default(false);

        $table->timestamps();

        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');

        $table->unique(['student_id', 'module_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diploma_final_module');
    }
};
