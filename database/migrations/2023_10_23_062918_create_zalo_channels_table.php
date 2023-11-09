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
        Schema::create('zalo_channels', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(Str::uuid()->toString());
            $table->string('name')->nullable();
            $table->string('project_id')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->bigInteger('oa_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->text('client_secret')->nullable();
            $table->bigInteger('status')->default(1)->comment('1: pending, 2: created success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zalo_channels');
    }
};
