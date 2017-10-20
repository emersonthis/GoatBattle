<?php
namespace App\GoatBattle;

use App\GoatBattle\Goat;

class Stilly extends Goat
{
    public $speed = 4;
    public $horns = 8;
    public $toughness = 8;
    public $name = "Stilly";

    /**
     */
    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
        $actions = [];
        if (!$this->isAtCenter()) {
            $actions[] = $this->face(0, 0, $myLocation);
            $actions[] = $this->move(1);
        } else {
            $actions[] = $this->face($opponentLocation->x, $opponentLocation->y, $myLocation);
            $actions[] = $this->ram();
        }
        // debug($this->location);
        // debug($myLocation);
        // debug($actions);
        // exit;
        return $actions;
    }

    /**
     */
    private function isAtCenter()
    {
        if ($this->location->x !== 0) {
            return false;
        }
        if ($this->location->y !== 0) {
            return false;
        }
        return true;
    }
}
