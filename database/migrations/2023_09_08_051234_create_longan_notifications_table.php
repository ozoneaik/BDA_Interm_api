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
        Schema::create('longan_notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type')->default(InnovationType::TRIMMING->value);
            $table->unsignedTinyInteger('amount_due_date');
            $table->foreignId('longan_id')
                ->references('id')
                ->on('longans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('longan_notifications');
    }
};
