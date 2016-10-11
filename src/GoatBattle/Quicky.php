<?php
namespace App\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use APP\GoatBattle\Quicky;

class Quicky extends Goat
{
    // private $speed = 10;
    // private $horns = 5;
    // private $toughness = 5;

    public $name = "Quicky";
    public $speed = 10;
    public $horns = 5;
    public $thoughness = 5;

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {        
        $actions1 = $this->turnToFaceAndAdvance($myLocation, $opponentLocation);
        // $actions2 = $this->turnToFaceAndAdvance($opponentLocation);
        // $action3 = (new Action($this))->ram();

        return $actions1; // + $actions2 + [$action3];
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
    public function turnToFaceAndAdvance(GoatLocation $myLocation, GoatLocation $opponentLocation)
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
            $actions[] = $this->turnTo(180, $myLocation);
            $actions[] = $this->move($myLocation->y - $opponentLocation->y - 1);
        } elseif ($east) {
            $actions[] = $this->turnTo(270, $myLocation);
            $actions[] = $this->move($myLocation->x - $opponentLocation->x - 1);
        } elseif ($south) {
            $actions[] = $this->turnTo(0, $myLocation);
            $actions[] = $this->move($opponentLocation->y - $myLocation->y - 1);
        } elseif ($west) {
            $actions[] = $this->turnTo(90, $myLocation);
            $actions[] = $this->move($opponentLocation->x - $myLocation->x - 1);
        }

        return $actions;
    }
}
