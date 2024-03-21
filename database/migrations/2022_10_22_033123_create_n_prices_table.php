<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('n_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('cost');
            $table->string('goodsSubType')->nullable();
            $table->string('priceType')->nullable();
            $table->string('bagUnits')->nullable();
            $table->string('bagValue')->nullable();
            $table->integer('client_id');
            $table->foreignId('nomenclature_id')->nullable()->constrained('nomenclatures')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('n_prices');
    }
}
