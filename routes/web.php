<?php

use App\Game;
use App\Tile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('launch', function(){

    $game  = new Game;
    $game->code = "TESTS";
    $game->save();

    for ($color=1; $color < 7; $color++) { 
        for ($shape=1; $shape < 7; $shape++) { 
            for ($i=0; $i < 3; $i++) { 
                $tile = new Tile;
                $tile->color = $color;
                $tile->shape = $shape;
                $tile->position_x = null;
                $tile->position_y = null;
                $tile->game_id = $game->id;
                $tile->save();
            }
        }
    }
    $firstTile = $game->tiles()->inRandomOrder()->first();
    $firstTile->position_x = 0;
    $firstTile->position_y = 0;
    $firstTile->save();
    dump($firstTile);
});

Route::get('tests/game/{code}', [TestController::class, 'boardgame']);

