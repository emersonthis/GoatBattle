<?php

namespace App\GoatBattle;

class Action
{
    const MOVE = 1;
    const TURN = 2;
    const RAM = 3;
    const RAM_COST = 5;

    public $actionsMap = [
        1 => 'MOVE', // takes a distance value
        2 => 'TURN', // takes a + or - roation
        3 => 'RAM'
    ];


    public $measure = 0;
    public $type;
    public $endLocation;

    /**
     *
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
                $this->measure = self::RAM_COST;
        }
    }

    /**
     *
     */
    public function cost()
    {
        if (!$this->type) {
            return false;
        }

        if ($this->type === self::RAM) {
            return self::RAM_COST;
        }

        if ($this->type === self::MOVE) {
            return $this->measure;
        }

        if ($this->type === self::TURN) {
            return abs($this->measure); //remove negative values
        }
    }

    // public function setEndLocation(GoatLocation $location)
    // {
    //     $this->endLocation = $location;
    // }

    /**
     *
     */
    public function number()
    {
        return $this->action;
    }

    /**
     *
     */
    public function name()
    {
        return $this->actionsMap[$this->action];
    }

    /**
     * Apply the action
     * @param Goat $thisGoat the goat doing the action
     * @param GoatLocation $thisGoatLocation the location of this goat
     * @param Goat $otherGoat the other goat
     * @param GoatLocation $otherGoatLocation the other goat's location
     * @return GoatLocation
     */
    public function apply(
        Goat $thisGoat,
        GoatLocation $thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation
    ) {
        $endLocation;

        if ($this->isMove($this)) {
            $endLocation = $this->moveGoat($thisGoatLocation, $otherGoatLocation);
        }
        if ($this->isTurn($this)) {
            $endLocation = $this->turnGoat($thisGoatLocation);
        }
        if ($this->isRam($this)) {
            $endLocation = $thisGoatLocation;
            $this->ramGoat($thisGoat, $thisGoatLocation, $otherGoat, $otherGoatLocation);
        }

        $this->endLocation = $endLocation;

        return $endLocation;
    }

    /**
     * Make a goat ram
     * @param Goat $thisGoat the goat doing the ramming
     * @param GoatLocation $thisGoatLocation the location of the ramming goat
     * @param Goat $otherGoat the goat being rammed
     * @param GoatLocation $otherGoatLocation the location of the other goat
     */
    private function ramGoat(
        Goat $thisGoat,
        GoatLocation $thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation
    ) {
        if ($this->isOtherGoatRammable($thisGoatLocation, $otherGoatLocation)) {
            $otherGoat->toughness = $otherGoat->toughness - $thisGoat->horns;
        }
    }

    /**
     * Return true or false if the other goat is in position for ramming
     * @param GoatLocation $rammingGoatLocation the location of the ramming goat
     * @param GoatLocation $otherGoatLocation the location of the goat to be rammed
     * @return bool
     */
    private function isOtherGoatRammable($rammingGoatLocation, $otherGoatLocation)
    {
        switch ($rammingGoatLocation->direction) {
            case 0:
            case 360:
                if ($rammingGoatLocation->x != $otherGoatLocation->x) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y + 1)) {
                    return false;
                }
                break;

            case 45:
                if ($rammingGoatLocation->x != ($otherGoatLocation->x - 1)) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y - 1)) {
                    return false;
                }
                break;

            case 90:
                if ($rammingGoatLocation->x != ($otherGoatLocation->x - 1)) {
                    return false;
                }
                if ($rammingGoatLocation->y != $otherGoatLocation->y) {
                    return false;
                }
                break;

            case 135:
                if ($rammingGoatLocation->x != ($otherGoatLocation->x + 1)) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y - 1)) {
                    return false;
                }
                break;

            case 180:
                if ($rammingGoatLocation->x != $otherGoatLocation->x) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y - 1)) {
                    return false;
                }
                break;

            case 225:
                if ($rammingGoatLocation->x != ($otherGoatLocation->x - 1)) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y - 1)) {
                    return false;
                }
                break;

            case 270:
                if ($rammingGoatLocation->x != $otherGoatLocation->x - 1) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y)) {
                    return false;
                }
                break;

            case 315:
                if ($rammingGoatLocation->x != ($otherGoatLocation->x - 1)) {
                    return false;
                }
                if ($rammingGoatLocation->y != ($otherGoatLocation->y + 1)) {
                    return false;
                }
                break;
        }
        return true;
    }

    /**
     *
     */
    private function turnGoat(GoatLocation $goatLocation)
    {
        $oldDirection = $goatLocation->direction;
        $newDirection = 45 * $this->measure;
        $newDirection = ($oldDirection + $newDirection) % 360;
        $newDirection = ($newDirection > 0) ? $newDirection : (360 + $newDirection);

        $goatLocation->direction = $newDirection;

        return $goatLocation;
    }

    /**
     * Trim the move location to stay in bounds
     * @param GoatLocation $location after action
     * @return GoatLocation
     */
    private function trimMoveToBounds(GoatLocation $location)
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
     * @param GoatLocation $location after action
     * @param GoatLocation $otherLocation of other goat
     * @return measure
     */
    private function trimMeasureIfBlocked(GoatLocation $location, GoatLocation $otherLocation)
    {
        $yDifference = $otherLocation->y - $location->y;
        $xDifference = $otherLocation->x - $location->x;
        $sameX = $location->x == $otherLocation->x;
        $sameY = $location->y == $otherLocation->y;
        $measure = $this->measure;

        //N
        if (($location->direction == 0) && $sameX && ($yDifference <= $this->measure)) {
            $measure = $yDifference - 1;
        }

        //NE
        if (($location->direction == 45) && ($yDifference == $xDifference) && ($yDifference <= $this->measure)) {
            $measure = $yDifference - 1;
        }

        //E
        if (($location->direction == 90) && $sameY && ($xDifference <= $this->measure)) {
            $measure = $xDifference - 1;
        }

        //SE
        if (($location->direction == 135) && ($xDifference == ($yDifference * -1)) && ($xDifference <= $this->measure)) {
            $measure = $xDifference - 1;
        }

        //S
        if (($location->direction == 180) && $sameX && ($yDifference <= $this->measure)) {
            $measure = $yDifference - 1;
        }

        //SW                                                                        could be $yDifference
        if (($location->direction == 225) && ($xDifference == $yDifference) && (abs($xDifference) <= $this->measure)) {
            $measure = abs($xDifference) - 1;
        }

        //W
        if (($location->direction == 270) && $sameY && ($xDifference <= $this->measure)) {
            $measure = abs($xDifference) - 1;
        }

        //NW                                                                        could be $yDifference
        if (($location->direction == 315) && ($yDifference == ($xDifference * -1)) && ($yDifference <= $this->measure)) {
            $measure = abs($xDifference) - 1;
        }

        return $measure;
    }

    /**
     * //@TODO Prevent goats from standing on top of one another!
     */
    private function moveGoat(GoatLocation $goatLocation, GoatLocation $otherGoatLocation)
    {
        $newLocation = clone $goatLocation;
        $n = $this->measure;
        $n = $this->trimMeasureIfBlocked($goatLocation, $otherGoatLocation);
        switch ($goatLocation->direction) {
            case 0:
            case 360:
            case 'N':
                $newLocation->y += $n;
                // can't go out of bounds
                // $newLocation->y = ($newLocation->y > 50) ? 50 : $newLocation->y;
                break;

            case 45:
            case 'NW':
                $newLocation->y += $n;
                $newLocation->x += $n;
                // //can't go out of bounds
                // $newLocation->y = ($newLocation->y > 50) ? 50 : $newLocation->y;
                // $newLocation->x = ($newLocation->x > 50) ? 50 : $newLocation->x;
                break;
            case 90:
            case 'E':
                $newLocation->x += $n;
                // $newLocation->x = ($newLocation->x > 50) ? 50 : $newLocation->x;
                break;

            case 135:
            case 'SE':
                $newLocation->x += $n;
                $newLocation->y -= $n;
                // $newLocation->y = ($newLocation->y < -50) ? -50 : $newLocation->y;
                // $newLocation->x = ($newLocation->x > 50) ? 50 : $newLocation->x;
                break;

            case 180:
            case 'S':
                $newLocation->y -= $n;
                // $newLocation->y = ($newLocation->y < -50) ? -50 : $newLocation->y;

                break;

            case 225:
            case 'SW':
                $newLocation->y -= $n;
                $newLocation->x -= $n;
                // $newLocation->y = ($newLocation->y < -50) ? -50 : $newLocation->y;
                // $newLocation->x = ($newLocation->x < -50) ? -50 : $newLocation->x;
                break;

            case 270:
            case 'W':
                $newLocation->x -= $n;
                // $newLocation->x = ($newLocation->x < -50) ? -50 : $newLocation->x;
                break;

            case 315:
            case 'NW':
                $newLocation->y += $n;
                $newLocation->x -= $n;
                // $newLocation->y = ($newLocation->y > 50) ? 50 : $newLocation->y;
                // $newLocation->x = ($newLocation->x < -50) ? -50 : $newLocation->x;
                break;
        }
        $newLocation = $this->trimMoveToBounds($newLocation);
        $goatLocation->x = $newLocation->x;
        $goatLocation->y = $newLocation->y;
        return $newLocation;
    }

    // /**
    //  *
    //  */
    // public function ram()
    // {
    //     $this->type = 3;
    // }

    /**
     *
     */
    public function isRam()
    {
        return ($this->type == self::RAM);
    }

    /**
     *
     */
    public function isTurn()
    {
        return ($this->type == self::TURN);
    }

    /**
     *
     */
    public function isMove()
    {
        return ($this->type == self::MOVE);
    }

    /**
     *
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
