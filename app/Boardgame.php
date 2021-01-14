<?php

namespace App;

use Illuminate\Support\Facades\Log;

class Boardgame {

    // Define position modificators for each direction
    protected $directions = [
        'up' => ['x' => -1, 'y' => 0],
        'right' => ['x' => 0, 'y' => 1],
        'down' => ['x' => 1, 'y' => 0],
        'left' => ['x' => 0, 'y' => -1],
    ];

    // Define the type of directions
    protected $directionTypes = [
        'up' => 'vertical',
        'down' => 'vertical',
        'left' => 'horizontal',
        'right' => 'horizontal',
    ];

    protected $types = [
        'color',
        'shape'
    ];

    public function __construct($tiles)
    {
        $this->boardgame = $tiles;
    }

    /**
    * Check if a tile is set on the provided position.
    */
    public function isPositionTaken($tile)
    {
        return isset($this->boardgame[$tile->position_x][$tile->position_y]);
    }

    /**
     * Add a tile to the current boardgame.
     */
    public function addTile($tile)
    {
        $this->boardgame[$tile->position_x][$tile->position_y] = $tile;
    }

    /**
    * Check if there are direct neighbours for a provided position and return them.
    */
    public function getNeighbours($tile)
    {
        $tiles = [];

        foreach($this->directions as $direction => $modificator) {
            // Define initial tile
            $neighbourX = $tile->position_x;
            $neighbourY = $tile->position_y;

            // Apply modificators in order to search through a direction
            $neighbourX += $modificator['x'];
            $neighbourY += $modificator['y'];
            // If there is a tile,  save it
            while(isset($this->boardgame[$neighbourX][$neighbourY])){

                $tiles[$this->directionTypes[$direction]][] = $this->boardgame[$neighbourX][$neighbourY];

                //Search the next tile
                $neighbourX += $modificator['x'];
                $neighbourY += $modificator['y'];
            }
        }
        return $tiles;
    }


    /**
     * Check if a tile can be played according to its neighbours.
     */
    public function isPlayable($tile, $neighbours)
    {

        // Loop through vertical and horizontal directions
        foreach ($neighbours as $direction) {
            // If only one neighbour for this direction, check for compatibility between the two tiles
            if(count($direction) == 1) {
                $neighbour = $direction[0];
                // Return true if shape OR color are the same, but not both.
                if (($neighbour->shape == $tile->shape AND $neighbour->color == $tile->color) OR
                ($neighbour->shape != $tile->shape AND $neighbour->color != $tile->color)) {
                    return false;
                }
            // Can't have more than 6 tiles in a block, including the played one
            } else if (count($direction) > 5) {
                return false;
            } else if (count($direction) > 1) {
                // Retrieve the "type" of the current direction
                $blockType = $this->getBlockType($direction);

                if(!$blockType) {
                    return false;
                }

                $matchingTile = $this->matchBlock($tile, $direction, $blockType);

                if(!$matchingTile) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Find the type of a given block and return it.
     */
    public function getBlockType($block)
    {
        $foundType = [
            'type' => '',
            'id' => 0
        ];

        // Loop on all types
        foreach($this->types as $type) {

            // Store the first ID we find. If it changes during the loop, the current type is not our block's type.
            $currentId = 0;
            foreach($block as $tile) {
                if($currentId === 0) {
                    $currentId = $tile->{$type};
                } else if($currentId != $tile->{$type}) {
                    $currentId = false;
                }
            }

            // If we have an ID stored, then we know what type of block it is.
            if($currentId !== false) {
                $foundType['type'] = $type;
                $foundType['id'] = $currentId;
                return $foundType;
            }
        }

        //  If no type have been found, return false.
        return false;

    }

    // Check if a tile can match a
    public function matchBlock($tile, $block, $blockType)
    {
        // If the played Tile type does not match the block type
        if($blockType['id'] != $tile->{$blockType['type']}) {
            return false;
        }

        // Retrieve the IDs for the secondary type of the block
        $secondaryType  = $this->getSecondaryType($blockType['type']);
        $secondaryIds = [];
        foreach($block as $blockTile) {
            $secondaryIds[] = intval($blockTile->{$secondaryType});
        }

        // If the played tiled secondary type is already present in the block, we can't play it
        if (in_array($tile->{$secondaryType}, $secondaryIds)) {
            return false;
        }

        return true;
    }

    //Return the secondary type of a block (e.g. color if the type is shape)
    public function getSecondaryType($type)
    {

        $types = $this->types;
        $mainType = array_search($type,$types);
        unset($types[$mainType]);

        return array_shift($types);
    }
}
