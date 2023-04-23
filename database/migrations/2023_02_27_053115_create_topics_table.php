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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('classrooms')->cascadeOnDelete();
            $table->string('name');
            $table->dateTime('date_time');
            $table->integer('no_of_modules');
            $table->integer('max_time_expert');
            $table->integer('max_time_jigsaw');
            $table->integer('transition_time');
            $table->string('status');
            $table->boolean('expert_form_group');
            $table->boolean('jigsaw_form_group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
