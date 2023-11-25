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
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->unsignedSmallInteger('amount_of_rai')->default(0);
            $table->unsignedSmallInteger('amount_of_square_wa')->default(0);
            $table->unsignedInteger('amount_of_tree');
            $table->unsignedDouble('age_of_rai')->nullable();
            $table->string('species')->nullable();
            $table->string('location');
            $table->date('trimming_date')->nullable();
            $table->string('picture_path')->nullable();

            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
