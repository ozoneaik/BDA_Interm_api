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
        Schema::table('farms', function (Blueprint $table) {
            $table->unsignedDouble('amount_of_square_meters')->nullable();
            $table->json('polygons')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            if(Schema::hasColumn('farms', 'amount_of_square_meters')) {
                $table->dropColumn('amount_of_square_meters');
            }

            if (Schema::hasColumn('farms', 'polygons')) {
                $table->dropColumn('polygons');
            }
        });
    }
};
