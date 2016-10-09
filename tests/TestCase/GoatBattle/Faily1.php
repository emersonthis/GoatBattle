<?php
namespace App\Test\TestCase\GoatBattle;
// namespace App\GoatBattle;
use App\GoatBattle\GoatLocation;

use App\GoatBattle\Goat;

class Faily1 extends Goat
{
    protected function setName()
    {
        return "Faily";
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
        return 18;
    }

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
        $actions = [];
        return $actions;
    }
}
