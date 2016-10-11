<?php

namespace App\GoatBattle;

class GoatLocation
{
    public $direction;
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
                $this->direction = 3 * 45; //135
                $this->x = -50;
                $this->y = 50;
                break;

            case 'BLUE':
                $this->direction = 7 * 45; //315
                $this->x = 50;
                $this->y = -50;
                break;

            default:
        }
    }

    public function describe()
    {
        return "@ {$this->x},{$this->y} facing {$this->facing()}";
    }

    /**
     * Facing
     * Returns the spoken description of the numeric heading
     * @return str
     */
    public function facing()
    {
        $string;
        switch ($this->direction) {
            case 0:
            case 360:
                $string = 'North';
                break;
            case 45:
                $string = 'Northeast';
                break;
            case 90:
                $string = 'East';
                break;
            case 135:
                $string = 'Southeast';
                break;
            case 180:
                $string = 'South';
                break;
            case 225:
                $string = 'Southwest';
                break;
            case 270:
                $string = 'West';
                break;
            case 315:
                $string = 'Northwest';
                break;
            default:
                debug($this->direction);
                throw new \Exception('Unrecognized direction');
        }
        return $string;
    }
}
