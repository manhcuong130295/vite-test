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
        Schema::table('line_channels', function (Blueprint $table) {
            //
            $table->bigInteger('status')->default(1)->comment('1: pending, 2: created success');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('line_channels', function (Blueprint $table) {
            //
        });
    }
};
