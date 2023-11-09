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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('type of plan');
            $table->string('image_path')->nullable();
            $table->unsignedInteger('max_project')->comment('max project can create');
            $table->unsignedInteger('max_character')->comment('max token of one project');
            $table->unsignedInteger('price')->comment('price of per month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
