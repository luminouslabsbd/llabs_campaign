<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHashQrCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hash_qr_code', function (Blueprint $table) {
            $table->id('id'); // Assuming you want to set id as the primary key
            $table->integer('tenant_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('purchase_value')->nullable();
            $table->text('hash');
            $table->string('qr_code_path');
            $table->timestamps(); // Add if you want timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hash_qr_code');
    }
}
