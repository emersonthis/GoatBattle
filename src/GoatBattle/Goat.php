<?php
namespace App\GoatBattle;

use App\GoatBattle\GoatLocation;

abstract class Goat
{
    protected $name;
    public $speed = 2;
    public $horns = 2;
    public $toughness = 2;
    public $location;
    public $color;

    /**
     *
     */
    final public function __construct(GoatLocation $location = null)
    {
        // $this->name = $this->setName();
        // $this->speed = $this->setSpeed();
        // $this->horns = $this->setHorns();
        // $this->toughness = $this->setToughness();
        $this->location = ($location) ? $location : new GoatLocation();
    }

    // abstract protected function setName();
    // abstract protected function setSpeed();
    // abstract protected function setHorns();
    // abstract protected function setToughness();

    public function name()
    {
        return $this->name;
    }
    public function speed()
    {
        return $this->speed;
    }
    public function horns()
    {
        return $this->horns;
    }
    public function toughness()
    {
        return $this->toughness;
    }

    final public function ouch($n)
    {
        $this->toughness -= $n;
        return $this->toughness;
    }

    final public function setLocation(GoatLocation $location)
    {
        $this->location = $location;
    }
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
     *
     */
    final public function move($n)
    {
        $action = new Action('MOVE', $n);
        return $action;
    }

    final protected function ram()
    {
        $action = new Action('RAM');
        return $action;
    }


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
