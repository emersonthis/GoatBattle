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
            return RAM_COST;
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
     *
     */
    public function apply(
        Goat $thisGoat,
        GoatLocation $thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation
    ) {
        $endLocation;

        if ($this->isMove($this)) {
            $endLocation = $this->moveGoat($thisGoatLocation);
        }
        if ($this->isTurn($this)) {
            $endLocation = $this->turnGoat($thisGoatLocation);
        }
        if ($this->isRam($this)) {
            //@TODO
        }

        $this->endLocation = $endLocation;

        return $endLocation;
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
     *
     */
    //@TODO Prevent goats from standing on top of one another!
    private function moveGoat(GoatLocation $goatLocation)
    {
        $newLocation = clone $goatLocation;
        $n = $this->measure;
        switch ($goatLocation->direction) {
            case 0:
            case 360:
            case 'N':
                $newLocation->y += $n;
                // can't go out of bounds
                $newLocation->y = ($newLocation->y > 50) ? 50 : $newLocation->y;
                break;

            case 45:
            case 'NW':
                $newLocation->y += $n;
                $newLocation->x += $n;
                //can't go out of bounds
                $newLocation->y = ($newLocation->y > 50) ? 50 : $newLocation->y;
                $newLocation->x = ($newLocation->x > 50) ? 50 : $newLocation->x;
                break;
            case 90:
            case 'E':
                $newLocation->x += $n;
                $newLocation->x = ($newLocation->x > 50) ? 50 : $newLocation->x;
                break;

            case 135:
            case 'SE':
                $newLocation->x += $n;
                $newLocation->y -= $n;
                $newLocation->y = ($newLocation->y < -50) ? -50 : $newLocation->y;
                $newLocation->x = ($newLocation->x > 50) ? 50 : $newLocation->x;
                break;

            case 180:
            case 'S':
                $newLocation->y -= $n;
                $newLocation->y = ($newLocation->y < -50) ? -50 : $newLocation->y;

                break;

            case 225:
            case 'SW':
                $newLocation->y -= $n;
                $newLocation->x -= $n;
                $newLocation->y = ($newLocation->y < -50) ? -50 : $newLocation->y;
                $newLocation->x = ($newLocation->x < -50) ? -50 : $newLocation->x;
                break;

            case 270:
            case 'W':
                $newLocation->x -= $n;
                $newLocation->x = ($newLocation->x < -50) ? -50 : $newLocation->x;
                break;

            case 315:
            case 'NW':
                $newLocation->y += $n;
                $newLocation->x -= $n;
                $newLocation->y = ($newLocation->y > 50) ? 50 : $newLocation->y;
                $newLocation->x = ($newLocation->x < -50) ? -50 : $newLocation->x;
                break;
        }
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
