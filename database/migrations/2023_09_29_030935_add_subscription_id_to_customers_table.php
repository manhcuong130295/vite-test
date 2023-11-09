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
            $table->string('subscription_id')->nullable()->after('customer_stripe_id');
            $table->bigInteger('subscription_active')->default(0)->after('subscription_id')->comment('0: not active, 1: active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('subscription_id');
            $table->dropColumn('subscription_active');
        });
    }
};
