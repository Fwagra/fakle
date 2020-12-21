<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    public function owner()
    {
        return $this->belongsTo(Player::class, 'owner');
    }

    public function currentPlayer()
    {
        return $this->belongsTo(Player::class, 'current_player');
    }

    public function tiles()
    {
        return $this->hasMany(Tile::class);
    }

    /**
     * Get the tiles of the current game classified in a bidirectional array 
     * Example : $boardgame[1][6] where 1 = position_x and 6 = position_y
     */
    public function getBoard()
    {
        $tiles = $this->tiles()->played()->get();

        $boardgame = [];

        foreach ($tiles as $tile) {
            $boardgame[$tile->position_x][$tile->position_y] = $tile;
        }
        return $boardgame;
    }

}
