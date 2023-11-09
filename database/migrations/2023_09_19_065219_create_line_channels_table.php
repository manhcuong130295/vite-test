<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('line_channels', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(Str::uuid()->toString());
            $table->string('name')->nullable();
            $table->string('access_token')->nullable();
            $table->string('project_id')->nullable();
            $table->string('channel_id')->nullable();
            $table->string('channel_secret')->nullable();
            $table->string('path_qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_channels');
    }
};
