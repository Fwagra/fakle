<?php

namespace App\Http\Controllers;

use App\Game;
use App\Boardgame;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function playTile(Request $request)
    {
        // @TODO: Get user and game from token
        $game = Game::where('code', 'TESTS')->first();


        $this->validate($request,[
            'tiles' => 'required|array',
            'tiles.*.x' => 'required|integer',
            'tiles.*.y' => 'required|integer',
        ]);

        $playedTiles = $request->input('tiles'); 

        //Check if the tiles ar contiguous
        $xContiguous = count(array_unique($request->input('tiles.*.x'))) == 1;
        $yContiguous = count(array_unique($request->input('tiles.*.y'))) == 1;

        if(count($playedTiles) > 1 && !($xContiguous XOR $yContiguous)){
            return $this->JSONerror("Tiles not contiguous");
        }

        $boardgame = new Boardgame($game->getBoard());

        // Loop on each tile to proceed with game's rules validations
        foreach($playedTiles as $playedTile) {

            $posX = $playedTile['x'];
            $posY = $playedTile['y'];

            // Check if the position is already taken
            if($boardgame->isPositionTaken($posX, $posY)) {
                return $this->JSONerror("A tile is already set on the position X: ". $posX . ", Y: ". $posY );
            }
            
            // Check if the contiguous tiles are not empty
            if($boardgame->directNeighbours($posX, $posY) == 0) {
                return $this->JSONerror("The position X: ". $posX . ", Y: ". $posY .  " is not adjacent to a played tile." );
            }
        }
        
    }
}
