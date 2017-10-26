<?php
namespace App\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use APP\GoatBattle\Quicky;

class Quicky extends Goat
{

    public $name = "Quicky";
    public $speed = 10;
    public $horns = 5;
    public $toughness = 5;

    /**
     *
     */
    public function action(Location $myLocation, Location $opponentLocation)
    {
        $actions1 = $this->turnToFaceAndAdvance($myLocation, $opponentLocation);
        $actions1[] = $this->ram();
        return $actions1; // + $actions2 + [$action3];
    }

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
