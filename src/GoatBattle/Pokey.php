<?php
namespace App\GoatBattle;

use App\GoatBattle\Goat;

class Pokey extends Goat
{
    public $speed = 4;
    public $horns = 8;
    public $toughness = 8;
    public $name = "Pokey";

    /**
     */
    public function action(Location $myLocation, Location $opponentLocation)
    {
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
