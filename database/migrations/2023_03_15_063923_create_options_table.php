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
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnDelete();
            $table->string('group_distribution');
            $table->string('time_method');
            $table->integer('tm1')->nullable();
            $table->integer('tm2')->nullable();
            $table->integer('tm3')->nullable();
            $table->integer('tm4')->nullable();
            $table->integer('tm5')->nullable();
            $table->integer('tm6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
