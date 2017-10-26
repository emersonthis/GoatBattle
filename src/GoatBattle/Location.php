<?php

namespace GoatBattle;

class Location
{
    public $direction;
    public $x;
    public $y;

    const MIN_X = -50;
    const MAX_X = 50;
    const MIN_Y = -50;
    const MAX_Y = 50;

    /**
     */
    public function __construct($pos = null)
    {
        switch ($pos) {
            case 'RED':
                $this->direction = 315; //SE
                $this->x = -50;
                $this->y = 50;
                break;
            case 'BLUE':
                $this->direction = 135; //NW
                $this->x = 50;
                $this->y = -50;
                break;

            default:
        }
    }

    /**
     */
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
                $string = 'East';
                break;
            case 45:
                $string = 'Northeast';
                break;
            case 90:
                $string = 'North';
                break;
            case 135:
                $string = 'Northwest';
                break;
            case 180:
                $string = 'West';
                break;
            case 225:
                $string = 'Southwest';
                break;
            case 270:
                $string = 'South';
                break;
            case 315:
                $string = 'Southeast';
                break;
            default:
                debug($this->direction);
                throw new \Exception('Unrecognized direction');
        }
        return $string;
    }
}
