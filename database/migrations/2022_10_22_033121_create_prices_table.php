<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::dropIfExists('prices');
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('cost')->nullable();
            $table->string('goodsSubType')->nullable();
            $table->string('priceType')->nullable();
            $table->string('bagUnits')->nullable();
            $table->string('bagValue')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnUpdate()->nullOnDelete();
            $table->unique(['name', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('prices');
    }
}
