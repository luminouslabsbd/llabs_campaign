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
        Schema::table('campaign_member', function (Blueprint $table) {
            $table->after('spinner_round', function (Blueprint $table) {
                $table->longText('rewards')->nullable();
            });

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_member', function (Blueprint $table) {
            //
        });
    }
};
