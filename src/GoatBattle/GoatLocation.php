<?php

namespace App\GoatBattle;

class GoatLocation
{
    private $direction;
    public $x;
    public $y;

    const MIN_X = -50;
    const MAX_X = 50;
    const MIN_Y = -50;
    const MAX_Y = 50;

    public function __construct($pos = null)
    {
        switch ($pos) {
            case 'RED':
                $this->direction = 3; //135
                $this->x = -50;
                $this->y = 50;
                break;

            case 'BLUE':
                $this->direction = 7; //315
                $this->x = 50;
                $this->y = -50;
                break;

            default:
        }
    }

    public function direction()
    {
        return $this->direction;
    }

    public function x()
    {
        return $this->x;
    }

    public function y()
    {
        return $this->y;
    }
    public function faceingMe()
    {

    }
}
