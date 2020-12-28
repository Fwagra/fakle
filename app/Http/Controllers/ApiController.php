<?php

namespace App\Http\Controllers;

use App\Game;
use App\Tile;
use App\Player;
use App\Boardgame;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function launchGame(Request $request)
    {
        $this->validate($request,[
            'player_name' => 'required|string',
        ]);

        // Generate the game's code
        $faker = Factory::create(); 
        $code = $faker->colorName .'-'. $faker->colorName .'-'. $faker->colorName;
        
        
        DB::transaction(function () use ($request, $code) {

            // Create the game
            $game = new Game;
            $game->code = $code;
            $game->save();

            // Generate the tiles for the game
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

            // Randomly select a tile as the first one
            $firstTile = $game->tiles()->inRandomOrder()->first();
            $firstTile->position_x = 0;
            $firstTile->position_y = 0;
            $firstTile->save();

            // Create the player and bind it to the game
            $player = new Player;
            $player->name = $request->input('player_name');
            $player->game_id = $game->id;
            $player->save();
        });

        return  response()->json($code);
    }
    /**
     * Endpoint handling a new tile(s) added to the boardgame by a player
     */
    public function playTile(Request $request)
    {
        // @TODO: Get user and game from token
        $game = Game::where('code', 'TESTS')->first();


        $this->validate($request,[
            'tiles' => 'required|array',
            'tiles.*.x' => 'required|integer',
            'tiles.*.y' => 'required|integer',
            'tiles.*.shape' => 'required|integer|min:1|max:6',
            'tiles.*.color' => 'required|integer|min:1|max:6',
        ]);

        $playedTiles = $request->input('tiles'); 

        //Check if the tiles ar contiguous
        $xContiguous = count(array_unique($request->input('tiles.*.x'))) == 1;
        $yContiguous = count(array_unique($request->input('tiles.*.y'))) == 1;

        if(count($playedTiles) > 1 && !($xContiguous XOR $yContiguous)){
            return $this->JSONerror("Tiles not contiguous");
        }

        $boardgame = new Boardgame($game->getBoard());

        $tilesToSave = [];

        // Loop on each tile to proceed with game's rules validations
        foreach($playedTiles as $playedTile) {

            $newTile = new Tile;
            $newTile->position_x = $playedTile['x']; 
            $newTile->position_y = $playedTile['y']; 
            $newTile->color = $playedTile['color']; 
            $newTile->shape = $playedTile['shape']; 

            // Check if the position is already taken
            if($boardgame->isPositionTaken($newTile)) {
                return $this->JSONerror("A tile is already set on the position X: ". $newTile->position_x . ", Y: ". $newTile->position_y );
            }
            
            $directNeighbours = $boardgame->getNeighbours($newTile);

            // Check if the contiguous tiles are not empty
            if( count($directNeighbours) == 0) {
                return $this->JSONerror("The position X: ". $newTile->position_x . ", Y: ". $newTile->position_y .  " is not adjacent to a played tile." );
            }
            
            //Check if the tile can be played
            $canBePlayed = $boardgame->isPlayable($newTile, $directNeighbours);
            
            if(!$canBePlayed) {
                return $this->JSONerror("The tile  cannot be played here." );
            }

            // Add the current tile to the boardgame so it can be taken into account for the next tiles validation.
            $boardgame->addTile($newTile);

            // The current tile is stored in order to be saved if the next tiles are playable.
            $tilesToSave[] = $newTile;
        }

        // If the previous foreach is over, all the tiles are playable. So we proceed to save them.
        foreach($tilesToSave as $newTile) {
            //@TODO: find the tile to save according to the current player
            $tile = Tile::where('position_x', null)
                    ->where('color', $newTile->color)
                    ->where('shape', $newTile->shape)
                    ->first();

            if($tile) {
                $tile->position_x = $newTile->position_x;
                $tile->position_y = $newTile->position_y;
                $tile->player_id = null;
                $tile->save();
            }
            else {
                return $this->JSONerror("The tile cannot be found." );

            }
        }
        
    }
}
