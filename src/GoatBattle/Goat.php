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
    final public function turn(int $n)
    {
        if ($n > 6 || $n < 6) {
            throw new Exception('Invalid turn parameter');
        }

        $this->location->direction += 45 * $n;
    }

    /**
     *
     */
    final public function advance($n)
    {
        switch ($this->location->direction()) {
            case 0:
            case 'N':
                $this->location->y += $n;
                break;

            case 45:
            case 'NW':
                $this->location->y += $n;
                $this->location->x += $n;
                break;
            case 90:
            case 'E':
                $this->location->x += $n;
                break;

            case 135:
            case 'SE':
                $this->location->x += $n;
                $this->location->y -= $n;
                break;

            case 180:
            case 'S':
                $this->location->y -= $n;
                break;

            case 225:
            case 'SW':
                $this->location->y -= $n;
                $this->location->x -= $n;
                break;

            case 270:
            case 'W':
                $this->location->x -= $n;
                break;

            case 315:
            case 'NW':
                $this->location->y += $n;
                $this->location->x -= $n;
                break;
        }
    }

    final protected function ram()
    {
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
