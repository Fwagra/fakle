<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('current_player')->nullable()->references('id')->on('players')->onDelete('set null');
            $table->foreignId('owner')->nullable()->references('id')->on('players')->onDelete('set null');
        });
        Schema::table('tiles', function (Blueprint $table) {
            $table->foreignId('game_id')->constrained();
            $table->foreignId('player_id')->nullable()->default(null)->constrained();
            $table->index(['game_id','position_x','position_y'], 'position_index');
        });
        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('game_id')->constrained();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('games_current_player_foreign');
            $table->dropForeign('games_owner_foreign');
        });
        Schema::table('tiles', function (Blueprint $table) {
            $table->dropForeign('tiles_game_id_foreign');
            $table->dropForeign('tiles_player_id_foreign');
            $table->dropIndex('position_index');
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign('game_id')->constrained();
        });
    }
}
