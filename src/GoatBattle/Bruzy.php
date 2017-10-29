<?php
namespace GoatBattle;

use GoatBattle\Action;
use GoatBattle\Goat;

class Bruzy extends Goat
{

    public $name = "Bruzy";
    public $speed = 14;
    public $horns = 2;
    public $toughness = 4;

    /**
     *
     */
    public function action(Situation $situation)
    {
        $myLocation = ($this->color == 'RED') ? $situation->redLocation : $situation->blueLocation;
        $opponentLocation = ($this->color == 'BLUE') ? $situation->redLocation : $situation->blueLocation;

        $actions = [];

        if ($this->nAway(4, $situation)) {
            $actions += $this->turnToFaceAndAdvance($myLocation, $opponentLocation);
            $actions[] = $this->ram();
            $actions[] = $this->turn(1);
            $actions[] = $this->move(4);
            $actions[] = $this->turn(4);
        } else {
            $nearLocation = $opponentLocation;
            $nearLocation->x += (($nearLocation->x + 4) > 50) ? 4 : -4;
            $actions += $this->turnToFaceAndAdvance($myLocation, $nearLocation);
            $actions[] = $this->face($opponentLocation->x, $opponentLocation->y, $myLocation);
        }

        return $actions;
    }

    /**
     */
    private function nAway($n, $situation)
    {
        return ($this->away($situation) <= $n);
    }

    /**
     */
    private function away($situation)
    {
        return sqrt(
            ($situation->redLocation->x - $situation->blueLocation->x)**2
            +
            ($situation->redLocation->y - $situation->blueLocation->y)**2
        );
    }

    // /**
    //  */
    // private function facing()
    // {
    // }

    /**
     *
     */
    public function turnToFaceAndAdvance(Location $myLocation, Location $opponentLocation)
    {
        $north = false;
        $east = false;
        $south = false;
        $west = false;

        $actions = [];

        # we're east
        if ($myLocation->x > $opponentLocation->x) {
            $east = true;
        }
        # we're west
        if ($myLocation->x < $opponentLocation->x) {
            $west = true;
        }
        # we're south
        if ($myLocation->y < $opponentLocation->y) {
            $south = true;
        }
        # we're north
        if ($myLocation->y > $opponentLocation->y) {
            $north = true;
        }

        if ($north) {
            $actions[] = Goat::turnTo(270, $myLocation);
            $actions[] = $this->move($myLocation->y - $opponentLocation->y);
        } elseif ($east) {
            $actions[] = Goat::turnTo(180, $myLocation);
            $actions[] = $this->move($myLocation->x - $opponentLocation->x);
        } elseif ($south) {
            $actions[] = Goat::turnTo(90, $myLocation);
            $actions[] = $this->move($opponentLocation->y - $myLocation->y);
        } elseif ($west) {
            $actions[] = Goat::turnTo(0, $myLocation);
            $actions[] = $this->move($opponentLocation->x - $myLocation->x);
        }

        return $actions;
    }
}
