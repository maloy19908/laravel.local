-- Active: 1687801176724@@127.0.0.1@3306@test
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnListingFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('products', 'listingFee')){
            Schema::table('products', function (Blueprint $table) {
                $table->string('listingFee')->default('Package');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('products', 'listingFee')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('listingFee');
            });
        }       
    }
}
