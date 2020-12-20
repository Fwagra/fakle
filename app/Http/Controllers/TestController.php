<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function boardgame($code)
    {

        $game = Game::where('code', $code)->first();

        if($game) {
            // Boardgame tiles ordered by position
            $tiles = $game->tiles()->played()->get();
            // Generate min and max available positions
            $minX = $game->tiles()->minX()->position_x - 1;
            $minY = $game->tiles()->minY()->position_y - 1;
            $maxX = $game->tiles()->maxX()->position_x + 1;
            $maxY = $game->tiles()->maxY()->position_y + 1;
            $boardgameWidth = ($maxY - $minY + 1) * 50;
            
            return view('boardgame', compact('tiles', 'minX', 'minY', 'maxX', 'maxY', 'boardgameWidth'));
        }
    }
}
