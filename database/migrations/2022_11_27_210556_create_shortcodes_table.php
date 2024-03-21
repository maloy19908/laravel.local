<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('shortcodes');
        Schema::create('shortcodes', function (Blueprint $table) {
            $table->id();
            $table->string('shortcode_name');
            $table->string('name')->unique();
            $table->longText('description');
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shortcodes');
    }
}
