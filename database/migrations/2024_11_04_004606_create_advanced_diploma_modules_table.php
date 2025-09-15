<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvancedDiplomaModulesTable extends Migration
{
    public function up()
    {
        Schema::create('advanced_diploma_modules', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->unsignedBigInteger('module_id');
            $table->string('course_title'); 
            $table->integer('credits')->default(10); 
            $table->boolean('is_special')->default(false);
            $table->timestamps();

             $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advanced_diploma_modules');
    }
}
