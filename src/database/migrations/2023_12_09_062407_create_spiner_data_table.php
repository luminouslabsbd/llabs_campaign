<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpinerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spiner_data', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('label_title');
            $table->string('label_value');
            $table->string('label_color');
            $table->integer('init_prize')->nullable();
            $table->integer('available_prize')->nullable();
            $table->boolean('is_wining_label')->default(false);
            $table->string('cam_id')->nullable();
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
        Schema::dropIfExists('spiner_data');
    }
}
