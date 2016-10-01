<?php
namespace App\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;

class Quicky extends Goat
{
    // private $speed = 10;
    // private $horns = 5;
    // private $toughness = 5;

    protected function setName(){
        return "Quicky";
    }
    protected function setSpeed() { return 10; }
    protected function setHorns() { return 5; }
    protected function setToughness() { return 5; }

    public function action(GoatLocation $opponentLocation)
    {
        $actions1 = $this->turnToFaceAndAdvance($opponentLocation);
        $actions2 = $this->turnToFaceAndAdvance($opponentLocation);
        //...

    }

    /**
     *
     */
    private function turnTo($direction)
    {
        if ($this->location->direction() - $direction > 4) {
            $a = new Action($this);
            return $a->turn(-1 * ($direction - $this->direction));
        }
        $a = new Action($this);
        return $a->turn($this->location->direction() - $direction);
    }

    /**
     *
     */
    private function turnToFaceAndAdvance(GoatLocation $opponentLocation)
    {
        $north = false;
        $east = false;
        $south = false;
        $west = false;

        $actions = [];

        # we're east
        if ($this->location->x > $opponentLocation->x) {
            $east = true;
        }
        # we're west
        if ($this->location->x < $opponentLocation->x) {
            $west = true;
        }
        # we're south
        if ($this->location->y < $opponentLocation->y) {
            $south = true;
        }
        # we're north
        if ($this->location->y > $opponentLocation->y) {
            $north = true;
        }

        if ($north) {
            $actions[] = $this->turnTo(4);
            $a = new Action($this);
            $a->advance($this->location->y - $opponentLocation->y - 1);
            $actions[] = $a;
        } elseif ($east) {
            $actions[] = $this->turnTo(2);
            $a = new Action($this);
            $actions[] = $a->advance($this->location->x - $opponentLocation->x - 1);
        } elseif ($south) {
            $actions[] = $this->turnTo(0);
            $a = new Action($this);
            $actions[] = $a->advance($opponentLocation->y - $this->location->y - 1);
        } elseif ($west) {
            $actions[] = $this->turnTo(6);
            $a = new Action($this);
            $actions[] = $a->advance($opponentLocation->x - $this->location->x - 1);
        }

        return $actions;
    }
}
