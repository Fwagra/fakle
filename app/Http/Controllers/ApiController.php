<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function playTile(Request $request)
    {
        // @TODO: Get user and game from token
        $game = Game::where('code', 'TESTS')->first();

        // Check for provided tiles
        // $tiles = $request->input('tiles');
        // if(!$tiles) {
        //     return response()->json(['error' => ["No tile(s) provided"]], 400);
        // }

        // $this->validate($request,[
        //     'tiles' => 'required|array',
        //     'tiles.*.x' => 'integer',
        //     'tiles.*.y' => 'integer',
        // ]);
        //Check if the tiles ar contiguous
        $xContiguous = count(array_unique($request->input('tiles.*.x'))) == 1;
        $yContiguous = count(array_unique($request->input('tiles.*.y'))) == 1;

        if(!($xContiguous XOR $yContiguous)){
            return response()->json(['error' => ["Tiles not contiguous"]], 400);
        }

        // Check if the position is already taken
        
        dump($xContiguous);
    }
}
