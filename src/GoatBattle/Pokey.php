<?php
namespace GoatBattle;

use GoatBattle\Goat;

class Pokey extends Goat
{
    public $speed = 4;
    public $horns = 8;
    public $toughness = 8;
    public $name = "Pokey";

    /**
     */
    public function action(Situation $situation)
    {
        $myLocation = ($this->color == 'RED') ? $situation->redLocation : $situation->blueLocation;
        $opponentLocation = ($this->color == 'BLUE') ? $situation->redLocation : $situation->blueLocation;
        $actions = [];
        if (!$this->isAtCenter($myLocation)) {
            $actions[] = $this->face(0, 0, $myLocation);
            $actions[] = $this->move(1);
        } else {
            $actions[] = $this->face($opponentLocation->x, $opponentLocation->y, $myLocation);
            $actions[] = $this->ram();
        }
        return $actions;
    }

    /**
     */
    private function isAtCenter($myLocation)
    {
        if ($myLocation->x !== 0) {
            return false;
        }
        if ($myLocation->y !== 0) {
            return false;
        }
        return true;
    }
}
