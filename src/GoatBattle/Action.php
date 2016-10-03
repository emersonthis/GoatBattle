<?php

namespace App\GoatBattle;

class Action
{

    public $actionsMap = [
        1 => 'advance', // takes a distance value
        2 => 'turn', // takes a + or - roation
        3 => 'ram'
    ];

    const ADVANCE = 1;
    const TURN = 2;
    const RAM = 3;

    // public $action = false;
    // public $distance = false;
    // public $direction = false;
    public $measure = 0;
    private $goat;
    public $endLocation;

    /**
     *
     */
    public function __construct(Goat $goat)
    {
        $this->goat = $goat;
    }

    /**
     *
     */
    public function cost()
    {
        if (!$this->number) {
            return false;
        }

        if ($this->number === self::RAM) {
            return 5;
        }

        if ($this->number === self::ADVANCE) {
            return $this->measure;
        }

        if ($this->number === self::TURN) {
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
        $this->number = 1;
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
        $this->number = 2;
        $this->measure = $direction;
    }

    /**
     *
     */
    public function ram()
    {
        $this->action = 3;
    }

    public function isRam()
    {
        return ($this->number == self::RAM);
    }

    public function isTurn()
    {
        return ($this->number == self::TURN);
    }

    public function isAdvance()
    {
        return ($this->number == self::ADVANCE);
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
