<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public function tiles()
    {
        return $this->belongsTo(Player::class);
    }
}
