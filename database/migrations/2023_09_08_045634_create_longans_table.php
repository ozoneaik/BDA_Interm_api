<?php

use App\Enums\InnovationType;
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
        Schema::create('longans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('location');
            $table->string('specie');
            $table->string('type')->nullable()->default(InnovationType::TRIMMING->value);
            $table->date('trimming_at')->nullable();
            $table->foreignId('farm_id')
                ->references('id')
                ->on('farms')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('longans');
    }
};
