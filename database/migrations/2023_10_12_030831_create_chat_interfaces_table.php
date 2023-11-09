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
        Schema::create('chat_interfaces', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->string('chatbot_name')->nullable();
            $table->string('theme_color')->nullable();
            $table->string('chatbot_picture')->nullable();
            $table->bigInteger('chatbot_picture_active')->default(0)->comment('0: active, 1 not active');
            $table->string('initial_message')->nullable();
            $table->longText('suggest_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_interfaces');
    }
};
