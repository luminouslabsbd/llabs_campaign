<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->string('name');
            $table->string('card_id');
            $table->bigInteger('template_id')->nullable();
            $table->string('price_check')->nullable();
            $table->string('point_check')->nullable();
            $table->string('tenant_id');
            $table->string('campain_code');
            $table->string('campaign_type');
            $table->text('hash')->nullable();
            $table->decimal('unit_price_for_coupon', 10, 0);
            $table->decimal('unit_price_for_point', 10, 0);
            $table->string('coupon')->nullable();
            $table->tinyInteger('status');
            $table->string('created_by');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
