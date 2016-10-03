<?php
namespace App\GoatBattle;

use App\GoatBattle\Goat;

class Stilly extends Goat
{
    // private $speed = 4;
    // private $horns = 8;
    // private $toughness = 8;

    protected function setName()
    {
        return "Stilly";
    }
    protected function setSpeed()
    {
        return 4;
    }
    protected function setHorns()
    {
        return 8;
    }
    protected function setToughness()
    {
        return 8;
    }

    public function action(GoatLocation $opponentLocation)
    {
        $actions = [];
        if (!$this->isAtCenter()) {
            $action = new Action($this);
            $action->advance(1);
            $actions[] = $action;
            // debug($actions);
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
