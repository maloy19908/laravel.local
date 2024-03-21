<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('clients');
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('recognize')->nullable();
            $table->string('phone_personal')->unique();
            $table->string('phone_personal_other')->nullable();
            $table->string('avito_url')->nullable();
            $table->string('site')->nullable();
            $table->string('email')->nullable();
            $table->string('comment')->nullable();
            $table->string('phone_avito')->nullable();
            $table->string('phone_yula')->nullable();
            $table->text('cars')->nullable();
            $table->string('delivery_cost')->nullable();
            $table->string('cars_other')->nullable();
            $table->string('order_pay')->nullable();
            $table->string('adress')->nullable();
            $table->string('min_cost')->nullable();
            $table->string('min_cost_unique')->nullable();
            $table->string('comment_other')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
