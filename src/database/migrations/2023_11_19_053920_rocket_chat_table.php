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
        Schema::create('rocket_chat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_url');
            $table->string('api_title');
            $table->text('api_token');
            $table->string('x_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rocket_chat');
    }
};
