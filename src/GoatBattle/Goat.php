<?php
namespace GoatBattle;

use GoatBattle\Location;

abstract class Goat
{
    public $name = null;
    public $speed = 2;
    public $horns = 2;
    public $toughness = 2;
    public $color;
    public $health;

    /**
     *
     */
    final public function __construct($color = null)
    {
        // $this->name = $this->setName();
        // $this->speed = $this->setSpeed();
        // $this->horns = $this->setHorns();
        // $this->toughness = $this->setToughness();
        // $this->location = ($location) ? $location : new Location();
        $this->health = ($this->toughness) ? $this->toughness : 1;
        if ($color == 'RED' || $color == 'BLUE') {
            $this->color = $color;
        }
    }

    /**
     *
     */
    public function name()
    {
        return $this->name;
    }

    /**
     *
     */
    public function speed()
    {
        return $this->speed;
    }

    /**
     *
     */
    public function horns()
    {
        return $this->horns;
    }

    /**
     *
     */
    public function toughness()
    {
        return $this->toughness;
    }

    /**
     *
     */
    abstract public function action(Situation $situation);

    /**
     *
     */
    final public function validateAttributes()
    {
        if ($this->speed < 1 || $this->horns < 1 || $this->toughness < 1) {
            return false;
        }
        if ($this->speed + $this->horns + $this->toughness > 20) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    final public function maxActionPerRound()
    {
        return $this->speed;
    }

    /**
     *
     */
    final public function turn($n)
    {
        $action = new Action('TURN', $n);
        return $action;
    }

    /**
     * Turn to
     * @param int $endDirection the direction to turn to
     * @param Location $myLocation the current goat location
     * @return Action
     */
    public function turnTo($endDirection, $myLocation)
    {
        if (!Action::validateDirection($endDirection)) {
            debug("Invalid direction");
            return $this->turn(0);
        }

        $endDirection = ($endDirection == 360) ? 0 : $endDirection;

        $turnDegree = $endDirection - $myLocation->direction;

        if (abs($turnDegree) > 180) {
            $turnDegree = (360 - (-1 * $turnDegree));
        }

        $turnMeasure = $turnDegree / 45;
        
        return $this->turn($turnMeasure);
    }

    /**
     * Face towards a coordinate
     * @param int $x the x coordinate to face
     * @param int $y the y coordinate to face
     * @param Location $myLocation the current location of this goat
     * @return Action
     * Be careful... PHP's atan2 function works differently than the conventions use so far
     * In geometry the 3:00 is considered "0" and positive rotations are counter-clockwise
     * So 9:00 = 180 etc. For historical reasons, this codebase consideres 12:00 = 0 and positive rotations
     * move clockwise. This is confusing and will be remedidied in the near future! @TODO
     */
    public function face($x, $y, $myLocation)
    {
        $radians = atan2(($y - $myLocation->y), ($x - $myLocation->x));
        $deg = $radians * (180 / pi()); //this value always assumes you're facing East
        
        $turnMeasure = $myLocation->direction - $deg; //IS THIS RIGHT?
        $turnMeasure = round($turnMeasure / 45);
        //fix 360 deg rotation
        $turnMeasure = (abs($turnMeasure) == 8) ? 0 : $turnMeasure;
        return $this->turn($turnMeasure);
    }

    /**
     *
     */
    final public function move($n)
    {
        $action = new Action('MOVE', $n);
        return $action;
    }

    /**
     *
     */
    final protected function ram()
    {
        $action = new Action('RAM');
        return $action;
    }


    /**
     *
     */
    final protected function approach(Location $location)
    {
    }

    /**
     * Is this goat okay?
     * @return bool
     */
    final public function ok()
    {
        if ($this->health > 0) {
            return true;
        }
    }
}
