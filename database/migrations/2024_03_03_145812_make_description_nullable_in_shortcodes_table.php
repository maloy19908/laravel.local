<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDescriptionNullableInShortcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            // Изменение столбца, чтобы он мог принимать null значения
            $table->longText('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            // Возвращение к предыдущему состоянию (обязательное поле)
            $table->longText('description')->nullable(false)->change();
        });
    }
}