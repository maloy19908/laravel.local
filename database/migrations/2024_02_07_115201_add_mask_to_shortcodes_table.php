<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaskToShortcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('shortcodes', 'mask')){
            Schema::table('shortcodes', function (Blueprint $table) {
                $table->string('mask')->default('');
                $table->dropUnique('shortcodes_name_unique');
                $table->string('name')->change();
                $table->dropColumn('shortcode_name');
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
        if (Schema::hasColumn('shortcodes', 'mask')) {
            Schema::table('shortcodes', function (Blueprint $table) {
                $table->dropColumn('mask');
                $table->string('name')->unique()->change();
                $table->string('shortcode_name');
            });
        }    
    }
}
