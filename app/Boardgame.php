<?php

namespace App;

class Boardgame {
    
    // Define position modificators for each direction
    protected $directions = [
        'up' => ['x' => -1, 'y' => 0],
        'right' => ['x' => 0, 'y' => 1],
        'down' => ['x' => 1, 'y' => 0],
        'left' => ['x' => 0, 'y' => 1],
    ];
    
    
    public function __construct($tiles)
    {
        $this->boardgame = $tiles;
    }
    
    /**
    * Check if a tile is set on the provided position.
    */
    public function isPositionTaken($x, $y)
    {
        return isset($this->boardgame[$x][$y]);
    }
    
    /**
    * Check if there are direct neighbours for a provided position.
    */
    public function directNeighbours($x, $y)     
    {
        $tiles = [];
        
        foreach($this->directions as $direction => $position) {
            $neighbourX = $x + $position['x'];
            $neighbourY = $y + $position['y'];

            if(isset($this->boardgame[$neighbourX][$neighbourY])){
                $tiles[] = $this->boardgame[$neighbourX][$neighbourY];
            }
        }

        return count($tiles);
    }
}