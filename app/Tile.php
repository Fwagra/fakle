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


    /** 
     * Gets only played tiles
     */
    public function scopePlayed($query)
    {
        return $query->where('position_x', '!=', null)->orderBy('position_x')->orderBy('position_y');
    }

    /** 
     * Get the tile with the lowest position_x in a set of tiles
     */
    public function scopeMinX($query)
    {
        return $query->orderBy('position_x', "asc")->first();
    }

    /** 
    * Get the tile with the lowest position_y in a set of tiles
    */
    public function scopeMinY($query)
    {
        return $query->orderBy('position_y', "asc")->first();
    }

    /** 
     * Get the tile with the highest position_x in a set of tiles
     */
    public function scopeMaxX($query)
    {
        return $query->orderBy('position_x', "desc")->first();
    }

    /** 
    * Get the tile with the highest position_y in a set of tiles
    */
    public function scopeMaxY($query)
    {
        return $query->orderBy('position_y', "desc")->first();
    }
}
