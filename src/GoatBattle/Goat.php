<?php
namespace App\GoatBattle;

use App\GoatBattle\GoatLocation;

abstract class Goat
{
    protected $name;
    public $speed = 2;
    public $horns = 2;
    public $toughness = 2;
    // public $location;
    public $color;

    /**
     *
     */
    final public function __construct()
    {
        // $this->name = $this->setName();
        // $this->speed = $this->setSpeed();
        // $this->horns = $this->setHorns();
        // $this->toughness = $this->setToughness();
        // $this->location = ($location) ? $location : new GoatLocation();
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
    final public function ouch($n)
    {
        $this->toughness -= $n;
        return $this->toughness;
    }

    // /**
    //  *
    //  */
    // final public function setLocation(GoatLocation $location)
    // {
    //     $this->location = $location;
    // }

    /**
     *
     */
    abstract public function action(GoatLocation $myLocation, GoatLocation $opponentLocation);

    /**
     *
     */
    final public function validateAttributes()
    {
        if ($this->speed > 10 || $this->horns > 10 || $this->toughness > 10) {
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
        // $oldDirection = $this->location->direction;
        // $newDirection = 45 * $n;
        // $newDirection = ($oldDirection + $newDirection) % 360;
        // $newDirection = ($newDirection > 0) ? $newDirection : (360 + $newDirection);
        // $this->location->direction = ($oldDirection + $newDirection) % 360;
        $action = new Action('TURN', $n);
        return $action;
    }

    /**
     * Turn to
     * @param int $endDirection the direction to turn to
     * @param GoatLocation $myLocation the current goat location
     * @return Action
     */
    public function turnTo($endDirection, $myLocation)
    {
        if (!Action::validateDirection($endDirection)) {
            debug("Invalid direction");
            return $this->turn(0);
        }

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
     * @param GoatLocation $myLocation the current location of this goat
     * @return Action
     * Be careful... PHP's atan2 function works differently than the conventions use so far
     * In geometry the 3:00 is considered "0" and positive rotations are counter-clockwise
     * So 9:00 = 180 etc. For historical reasons, this codebase consideres 12:00 = 0 and positive rotations
     * move clockwise. This is confusing and will be remedidied in the near future! @TODO @TODO @TODO
     */
    public function face($x, $y, $myLocation)
    {
        $radians = atan2(($y - $myLocation->y), ($x - $myLocation->x));
        $deg = $radians * (180 / pi()); //this value always assumes you're facing East
        $turnMeasure = $deg + $myLocation->direction - 90; //<-- see above
        $turnMeasure = round($turnMeasure / 45) * -1; //flip the sign because we rotate the opposite direction
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
    final protected function approach(GoatLocation $location)
    {
    }

    /**
     * Is this goat okay?
     * @return bool
     */
    final public function ok()
    {
        if ($this->toughness > 0) {
            return true;
        }
    }
}
