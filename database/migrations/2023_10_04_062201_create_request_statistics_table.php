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
        Schema::create('request_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('user_uuid');
            $table->string('subscription_plan_id');
            $table->integer('total_request')->comment('total request of user per month')->default(0);
            $table->integer('month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_statistics');
    }
};
