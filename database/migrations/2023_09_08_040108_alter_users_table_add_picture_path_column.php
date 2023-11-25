<?php

use App\Enums\UserApproveStatus;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('picture_path')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('package')->default(0);
            $table->string('status')->default(UserApproveStatus::PENDING->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'picture_path')){
                $table->dropColumn('picture_path');
            }

            if(Schema::hasColumn('users', 'phone')){
                $table->dropColumn('phone');
            }

            if(Schema::hasColumn('users', 'package')){
                $table->dropColumn('package');
            }

            if(Schema::hasColumn('users', 'status')){
                $table->dropColumn('status');
            }
        });
    }
};
