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
            return 5;
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

    // /**
    //  *
    //  */
    // public function result($activeGoat, $activeGoatLocation, $otherGoat, $otherGoatLocation)
    // {
    //     $result;
    //     switch ($this->type) {
    //         case self::MOVE:
    //             $result = [
    //                 'activeGoat' => $activeGoat,
    //                 'activeGoatLocation' => $this->moveGoat($activeGoatLocation),
    //                 'otherGoat' => $otherGoat,
    //                 'otherGoatLocation' => $otherGoatLocation
    //             ];
    //             break;

    //         //@TODO This does nothing now
    //         case self::TURN:
    //             $result = [
    //                 'activeGoat' => $activeGoat,
    //                 'activeGoatLocation' => $activeGoatLocation,
    //                 'otherGoat' => $otherGoat,
    //                 'otherGoatLocation' => $otherGoatLocation
    //             ];
    //             break;
    //     }
    //     return $result;
    // }

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

    private function turnGoat(GoatLocation $goatLocation)
    {
        $oldDirection = $goatLocation->direction;
        $newDirection = 45 * $this->measure;
        $newDirection = ($oldDirection + $newDirection) % 360;
        $newDirection = ($newDirection > 0) ? $newDirection : (360 + $newDirection);

        // $newLocation = new GoatLocation();
        // $newLocation->x = $goatLocation->x;
        // $newLocation->y = $goatLocation->y;
        // $newLocation->direction = $newDirection;

        $goatLocation->direction = $newDirection;

        return $goatLocation;
    }

    /**
     *
     */
    private function moveGoat(GoatLocation $goatLocation)
    {
        $newLocation = clone $goatLocation;
        $n = $this->measure;
        switch ($goatLocation->direction) {
            case 0:
            case 360:
            case 'N':
                $newLocation->y += $n;
                break;

            case 45:
            case 'NW':
                $newLocation->y += $n;
                $newLocation->x += $n;
                break;
            case 90:
            case 'E':
                $newLocation->x += $n;
                break;

            case 135:
            case 'SE':
                $newLocation->x += $n;
                $newLocation->y -= $n;
                break;

            case 180:
            case 'S':
                $newLocation->y -= $n;
                break;

            case 225:
            case 'SW':
                $newLocation->y -= $n;
                $newLocation->x -= $n;
                break;

            case 270:
            case 'W':
                $newLocation->x -= $n;
                break;

            case 315:
            case 'NW':
                $newLocation->y += $n;
                $newLocation->x -= $n;
                break;
        }
        $goatLocation->x = $newLocation->x;
        $goatLocation->y = $newLocation->y;
        return $goatLocation;
    }

    /**
     *
     */
    public function ram()
    {
        $this->type = 3;
    }

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
