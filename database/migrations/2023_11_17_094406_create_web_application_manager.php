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
        Schema::create('web_application_manager', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('picture_path')->nullable();
            $table->string('background_style')->nullable();
            $table->string('button_style')->nullable();
            $table->boolean('show_status')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_application_manager');
    }
};
