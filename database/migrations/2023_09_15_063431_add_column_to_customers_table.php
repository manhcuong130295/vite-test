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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('user_uuid')->unique();
            $table->bigInteger('subscription_plans_id')->default(1)->comment('0: free, 1: standard, 2: premium');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('user_uuid');
            $table->dropColumn('subscription_plans_id');
            $table->dropColumn('start_date');
            $table->dropColumn('due_date');
        });
    }
};
