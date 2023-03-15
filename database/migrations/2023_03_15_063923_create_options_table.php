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
            $table->float('tm1', 2)->nullable();
            $table->float('tm2', 2)->nullable();
            $table->float('tm3', 2)->nullable();
            $table->float('tm4', 2)->nullable();
            $table->float('tm5', 2)->nullable();
            $table->float('tm6', 2)->nullable();
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
