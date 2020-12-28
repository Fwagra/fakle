<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditPlayerAndGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('order')->nullable();
        });
        Schema::table('games', function (Blueprint $table) {
            $table->string('code', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('order');
        });
        Schema::table('games', function (Blueprint $table) {
            $table->string('code', 5)->change();
        });
    }
}
