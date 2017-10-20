<?php
namespace App\GoatBattle;

use App\GoatBattle\Goat;

class Stilly extends Goat
{
    public $speed = 4;
    public $horns = 8;
    public $toughness = 8;
    public $name = "Stilly";

    // protected function setName()
    // {
    //     return "Stilly";
    // }
    // protected function setSpeed()
    // {
    //     return 4;
    // }
    // protected function setHorns()
    // {
    //     return 8;
    // }
    // protected function setToughness()
    // {
    //     return 8;
    // }

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
        $actions = [];
        if (!$this->isAtCenter()) {
            $actions[] = $this->face(0,0);
            $actions[] = $this->move(1);
        }
        return $actions;
    }

    private function isAtCenter()
    {
        if ($this->location->x) {
            return false;
        }
        if ($this->location->y) {
            return false;
        }
        return true;
    }

}
