<?php

use App\Enums\LonganTreeDirections;
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
        Schema::create('picture_longan_trees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('picture_path')->nullable();
            $table->string('directions');
            $table->tinyInteger('seq')->nullable();
            $table->foreignId('longans_id')
                ->references('id')
                ->on('longans')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picture_longan_trees');
    }
};
