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
        Schema::create('spiner_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('spiner_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spiner_partner_id')->nullable()->constrained('spiner_partners')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('spiner_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spiner_campaign_id')->nullable()->constrained('spiner_campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('spiner_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spiner_member_id')->nullable()->constrained('spiner_members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('campaign_member_id')->nullable()->constrained('campaign_member')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('reward')->nullable();
            $table->timestamps();
        });

        Schema::create('spiner_point', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->cascadeOnUpdate()->cascadeOnDelete();
            $table->bigInteger('template_id')->nullable();
            $table->string('template_pass_type')->nullable();
            $table->foreignId('card_id')->nullable()->constrained('cards')->cascadeOnUpdate()->cascadeOnDelete();
            $table->bigInteger('point')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spiner_partners');
        Schema::dropIfExists('spiner_campaigns');
        Schema::dropIfExists('spiner_members');
        Schema::dropIfExists('spiner_rewards');
        Schema::dropIfExists('spiner_point');
    }
};
