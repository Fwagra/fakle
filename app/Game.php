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
}
