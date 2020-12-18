<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->boolean('launched')->default(false);
            $table->timestamps();
        });

        Schema::create('tiles', function (Blueprint $table) {
            $table->id();
            $table->enum('shape', [1,2,3,4,5,6]);
            $table->enum('color', [1,2,3,4,5,6]);
            $table->tinyInteger('position_x')->nullable()->default(null);
            $table->tinyInteger('position_y')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('action_done')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('tiles');
        Schema::dropIfExists('players');
    }
}
