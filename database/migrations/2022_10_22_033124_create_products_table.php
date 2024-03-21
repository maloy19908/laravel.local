<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('avito_id')->default('');
            $table->string('my_id')->unique()->default('');
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('product_uniq_title_id')->nullable()->constrained('productUniqTitle')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('town_id')->nullable()->constrained('towns')->cascadeOnUpdate()->nullOnDelete();
            $table->string('address_street')->nullable();
            $table->foreignId('category_id')->nullable()->constrained("categories")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('nomenclature_id')->nullable()->constrained('nomenclatures')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('dateBegin')->nullable();
            $table->string('productStatus')->nullable();
            $table->string('listingFee')->default('Package');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        
    }
}
