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
        Schema::create('member_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('card_id')->nullable()->constrained('cards')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('campagin_id')->nullable()->constrained('campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('spinner_prize')->nullable();
            $table->integer('spinner_point')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_cards');
    }
};
