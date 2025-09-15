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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('candidate_number')->unique();
            $table->string('email')->unique();
            $table->unsignedBigInteger('course_id');
            $table->string('course');
            $table->date('date_of_birth');
            $table->date('enrollment_date');
            $table->string('gender');
            $table->integer('cell_No');
            $table->string('address');
            $table->string('group');
            $table->string('occupation');
            $table->string('id_number')->unique();
            $table->string('profile_picture')->nullable();
            $table->string('nationality')->nullable();
            $table->string('emergency_contact')->nullable(); 
            $table->timestamps();

            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
