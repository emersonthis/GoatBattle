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

    public $action = false;
    public $distance = false;
    public $direction = false;
    private $goat;

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
        if (!$this->action) {
            return false;
        }

        if ($this->action === RAM) {
            return 5;
        }

        if ($this->action === ADVANCE) {
            return $this->distance;
        }

        if ($this->action === TURN) {
            return abs($this->direction); //remove negative values
        }
    }


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
        $this->action = 1;
        $this->distance = $distance;
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
        $this->action = 2;
        $this->direction = $direction;
    }

    /**
     *
     */
    public function ram()
    {
        $this->action = 3;
    }
}
