<?php

namespace GoatBattle;

class Action
{
    const MOVE = 1;
    const TURN = 2;
    const RAM = 3;

    public $actionsMap = [
        1 => 'MOVE', // takes a distance value
        2 => 'TURN', // takes a + or - roation
        3 => 'RAM'
    ];

    public $measure = 0;
    public $type;

    public $startSituation;
    public $endSituation;

    public $goatColor;

    /**
     * Construct
     */
    public function __construct($actionName, $measure = null)
    {
        if (!in_array($actionName, $this->actionsMap)) {
            return false;
        }

        $actionName = strtoupper($actionName);

        switch ($actionName) {
            case "MOVE":
                $this->type = 1;
                $this->measure = $measure;
                break;
            case "TURN":
                $this->type = 2;
                $this->measure = $measure;
                break;
            case "RAM":
                $this->type = 3;
        }
    }

    /**
     * Determine the cost of an action
     * @return int
     */
    public function cost(Goat $goat)
    {
        if (!$this->type) {
            return false;
        }

        if ($this->type === self::RAM) {
            $diff = $goat->horns - $goat->toughness;
            return ($diff < 1) ? 1 : $diff;
        }

        if ($this->type === self::MOVE) {
            return $this->measure;
        }

        if ($this->type === self::TURN) {
            return abs($this->measure); //remove negative values
        }
    }

    // /**
    //  *
    //  */
    // public function number()
    // {
    //     return $this->action;
    // }

    /**
     * Name of the action
     * @return str
     */
    public function name()
    {
        return $this->actionsMap[$this->action];
    }

    /**
     * Apply the action
     * @param Goat $thisGoat the goat doing the action
     * @param Location $thisLocation the location of this goat
     * @param Goat $otherGoat the other goat
     * @param Location $otherLocation the other goat's location
     * @return Location
     */
    public function apply(
        Goat $thisGoat,
        Situation $situation
    ) {
        if (!$thisGoat->color) {
            throw new \Exception('Color not set in Action::apply()');
        }

        $this->goatColor = $thisGoat->color;

        $this->startSituation = clone $situation;

        $thisLocation = ($thisGoat->color == 'RED') ? $situation->redLocation : $situation->blueLocation;
        $otherLocation = ($thisGoat->color == 'BLUE') ? $situation->redLocation : $situation->blueLocation;
        $otherGoat = ($thisGoat->color == 'RED') ? $situation->blueGoat : $situation->redGoat;

        if ($this->isMove($this)) {
            $endLocation = $this->moveGoat($thisLocation, $otherLocation);
        }
        if ($this->isTurn($this)) {
            $endLocation = $this->turnGoat($thisLocation);
        }
        if ($this->isRam($this)) {
            $endLocation = $thisLocation;
            $this->ramGoat($thisGoat, $thisLocation, $otherGoat, $otherLocation);
        }
        $thisLocation = $endLocation;

        $this->endSituation = new Situation([
            'redGoat' => ($thisGoat->color == 'RED') ? $thisGoat : $otherGoat,
            'blueGoat' => ($thisGoat->color == 'BLUE') ? $thisGoat : $otherGoat,
            'redLocation' => ($thisGoat->color == 'RED') ? $thisLocation : $otherLocation,
            'blueLocation' => ($thisGoat->color == 'BLUE') ? $thisLocation : $otherLocation
        ]);
        
        // return clone $endLocation;
        return $this->endSituation;
    }

    /**
     * Make a goat ram
     * @param Goat $thisGoat the goat doing the ramming
     * @param Location $thisLocation the location of the ramming goat
     * @param Goat $otherGoat the goat being rammed
     * @param Location $otherLocation the location of the other goat
     */
    private function ramGoat(
        Goat $thisGoat,
        Location $thisLocation,
        Goat &$otherGoat,
        Location $otherLocation
    ) {
        if ($this->isOtherGoatRammable($thisLocation, $otherLocation)) {
            //@TODO This is where to adjust for defensive horns
            $otherGoat->health -= $thisGoat->horns;
        }
    }

    /**
     * Return true or false if the other goat is in position for ramming
     * @param Location $rammingLocation the location of the ramming goat
     * @param Location $otherLocation the location of the goat to be rammed
     * @return bool
     */
    public static function isOtherGoatRammable($rammingLocation, $otherLocation)
    {
        switch ($rammingLocation->direction) {
            //E
            case 0:
            case 360:
                if ($rammingLocation->x != ($otherLocation->x - 1)) {
                    return false;
                }
                if ($rammingLocation->y != $otherLocation->y) {
                    return false;
                }
                break;

            //NE
            case 45:
                if ($rammingLocation->x != ($otherLocation->x - 1)) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y - 1)) {
                    return false;
                }
                break;

            //N
            case 90:
                if ($rammingLocation->x != $otherLocation->x) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y - 1)) {
                    return false;
                }
                break;


            // NW
            case 135:
                if ($rammingLocation->x != ($otherLocation->x + 1)) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y - 1)) {
                    return false;
                }
                break;

            //W
            case 180:
                if ($rammingLocation->x != ($otherLocation->x + 1)) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y)) {
                    return false;
                }
                break;

            // SW
            case 225:
                if ($rammingLocation->x != ($otherLocation->x + 1)) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y + 1)) {
                    return false;
                }
                break;

            //S
            case 270:
                if ($rammingLocation->x != $otherLocation->x) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y + 1)) {
                    return false;
                }
                break;

            // SE
            case 315:
                if ($rammingLocation->x != ($otherLocation->x - 1)) {
                    return false;
                }
                if ($rammingLocation->y != ($otherLocation->y + 1)) {
                    return false;
                }
                break;
        }
        return true;
    }

    /**
     * Turn the goat
     * @param Location $goatLocation the location of the goat before turn
     * @return Location the new location after turning
     */
    private function turnGoat(Location &$goatLocation)
    {
        $oldDirection = $goatLocation->direction;
        $newDirection = 45 * $this->measure;
        $newDirection = ($oldDirection + $newDirection) % 360;
        $newDirection = ($newDirection > 0) ? $newDirection : (360 + $newDirection);

        $goatLocation->direction = $newDirection;

        return clone $goatLocation;
    }

    /**
     * Trim the move location to stay in bounds
     * @param Location $location after action
     * @return Location
     */
    //@TODO These don't prevent moving diagonally "up" an edge etc
    private function trimMoveToBounds(Location $location)
    {
        if ($location->y > 50) {
            $location->y = 50;
        }
        if ($location->x > 50) {
            $location->x = 50;
        }
        if ($location->y < -50) {
            $location->y = -50;
        }
        if ($location->x < -50) {
            $location->x = -50;
        }
        return $location;
    }

    /**
     * Trim the move location to not pass thru other goat
     * @param Location $location after action
     * @param Location $otherLocation of other goat
     * @return measure
     */
    private function trimMeasureIfBlocked(Location $location, Location $otherLocation)
    {
        $yDifference = abs($otherLocation->y - $location->y);
        $xDifference = abs($otherLocation->x - $location->x);
        $sameX = $location->x == $otherLocation->x;
        $sameY = $location->y == $otherLocation->y;
        $measure = $this->measure;

        //N
        if (($location->direction == 90) && $sameX && ($yDifference <= $this->measure)) {
            $measure = $yDifference - 1;
        }

        //NE
        if (($location->direction == 45) && ($yDifference == $xDifference) && ($yDifference <= $this->measure)) {
            $measure = $yDifference - 1;
        }

        //E
        if (($location->direction == 0 || $location->direction == 360) && $sameY && ($xDifference <= $this->measure)) {
            $measure = $xDifference - 1;
        }

        //SE
        if (($location->direction == 315) && ($xDifference == $yDifference) && ($otherLocation->y < $location->y) && ($otherLocation->x > $location->x) && ($xDifference <= $this->measure)) {
            $measure = $xDifference - 1;
        }

        //S
        if (($location->direction == 270) && $sameX && ($yDifference <= $this->measure)) {
            $measure = $yDifference - 1;
        }

        //SW                                                                        could be $yDifference
        if (($location->direction == 225) && ($xDifference == $yDifference) && ($xDifference <= $this->measure)) {
            $measure = abs($xDifference) - 1;
        }

        //W
        if (($location->direction == 180) && $sameY && ($xDifference <= $this->measure)) {
            $measure = abs($xDifference) - 1;
        }

        //NW                                                                        could be $yDifference
        if (($location->direction == 135) && ($yDifference == $xDifference) && ($location->x > $otherLocation->x) && ($location->y < $otherLocation->y) && ($yDifference <= $this->measure)) {
            $measure = abs($xDifference) - 1;
        }

        return $measure;
    }

    /**
     * Move the goat
     * @param Location $goatLocation the location of this goat
     * @param Location $otherLocation the location of the other goat
     * @return Location the ending location after the move
     */
    private function moveGoat(Location &$goatLocation, Location $otherLocation)
    {
        $this->measure = $this->trimMeasureIfBlocked($goatLocation, $otherLocation);
        $n = $this->measure;
        
        switch ($goatLocation->direction) {
            case 360:
            case 0:
            case 'E':
                $goatLocation->x += $n;
                break;

            case 45:
            case 'NE':
                $goatLocation->y += $n;
                $goatLocation->x += $n;
                break;

            case 90:
            case 'N':
                $goatLocation->y += $n;
                break;

            case 135:
            case 'NW':
                $goatLocation->y += $n;
                $goatLocation->x -= $n;
                break;

            case 180:
            case 'W':
                $goatLocation->x -= $n;
                break;

            case 225:
            case 'SW':
                $goatLocation->y -= $n;
                $goatLocation->x -= $n;
                break;

            case 270:
            case 'S':
                $goatLocation->y -= $n;
                break;

            case 315:
            case 'SE':
                $goatLocation->x += $n;
                $goatLocation->y -= $n;
                break;
        }
        
        $goatLocation = $this->trimMoveToBounds($goatLocation);
        return clone $goatLocation;
    }

    /**
     * is this action a ram?
     * @return bool
     */
    public function isRam()
    {
        return ($this->type == self::RAM);
    }

    /**
     * is this action a turn?
     * @return bool
     */
    public function isTurn()
    {
        return ($this->type == self::TURN);
    }

    /**
     * is this action a move?
     * @return bool
     */
    public function isMove()
    {
        return ($this->type == self::MOVE);
    }

    /**
     * Describe the action in words
     * @return str
     */
    public function describe()
    {
        $string;
        switch ($this->type) {
            case self::RAM:
                $string = "Rams!";
                break;
            case self::TURN:
                $string = "Turns " . $this->measure;
                break;
            case self::MOVE:
                $string = "Moves " . $this->measure;
                break;
        }
        return $string;
    }

    /**
     * Validate direction
     * @param int $direction 0-360
     * @return bool true if valid false otherwise
     */
    public static function validateDirection($direction)
    {
        if (!is_int($direction)) {
            return false;
        }
        switch ($direction) {
            case 0:
            case 360:
            case 45:
            case 90:
            case 135:
            case 180:
            case 225:
            case 270:
            case 315:
                $valid = true;
                break;
            default:
                $valid = false;
        }
        return $valid;
    }
}
