<?php

namespace App\GoatBattle;

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

    /**
     *
     */
    public function __construct($actionName, $measure = null)
    {
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

    /**
     *
     */
    public function advance($distance)
    {
        $this->type = 1;
        $this->measure = $distance;
    }

    /**
     * Turn
     * @param int $direction 1-6
     * 1 = 45 right
     * 2 = 90 right
     * 3 = 135 right
     * 4 = 180 right
     * 5 = 225 right
     * 6 = 270 right
     * -1 = 45 left
     * -2 = 90 left
     * -3 = 135 left
     * -4 = 180 left
     * -5 = 225 left
     * -6 = 270 left
     */
    public function turn($direction)
    {
        $this->type = 2;
        $this->measure = $direction;
    }

    /**
     *
     */
    public function ram()
    {
        $this->type = 3;
    }

    public function isRam()
    {
        return ($this->type == self::RAM);
    }

    public function isTurn()
    {
        return ($this->type == self::TURN);
    }

    public function isAdvance()
    {
        return ($this->type == self::ADVANCE);
    }

    public function describe()
    {
        $string;
        switch ($this->number) {
            case self::RAM:
                $string = "Rams!";
                break;
            case self::TURN:
                $string = "Turns " . $this->measure;
                break;
            case self::ADVANCE:
                $string = "Advances " . $this->measure;
                break;
        }
        return $string;
    }
}
