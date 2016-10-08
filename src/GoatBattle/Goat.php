<?php
namespace App\GoatBattle;

use App\GoatBattle\GoatLocation;

abstract class Goat
{
    private $name;
    private $speed;
    private $horns;
    private $toughness;
    public $location;
    public $color;

    /**
     *
     */
    final public function __construct(GoatLocation $location = null)
    {
        $this->name = $this->setName();
        $this->speed = $this->setSpeed();
        $this->horns = $this->setHorns();
        $this->toughness = $this->setToughness();
        $this->location = ($location) ? $location : new GoatLocation();
    }

    abstract protected function setName();
    abstract protected function setSpeed();
    abstract protected function setHorns();
    abstract protected function setToughness();

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
    abstract public function action(GoatLocation $opponentLocation);

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
     *
     */
    final public function ok()
    {
        if ($this->toughness > 0) {
            return true;
        }
    }
}
