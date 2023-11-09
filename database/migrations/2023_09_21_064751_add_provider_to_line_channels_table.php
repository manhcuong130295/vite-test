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
            $table->string('provider_name')->nullable()->after('uuid');
            $table->text('provider_description')->nullable()->after('provider_name');
            $table->string('path_icon')->nullable()->after('provider_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('line_channels', function (Blueprint $table) {
            $table->dropColumn('provider_name');
            $table->dropColumn('provider_description');
            $table->dropColumn('path_icon');
        });
    }
};
