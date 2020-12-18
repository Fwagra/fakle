<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
