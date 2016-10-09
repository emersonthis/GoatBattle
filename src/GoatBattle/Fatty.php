<?php
namespace App\GoatBattle;

use App\GoatBattle\Goat;

class Fatty extends Goat
{
    private $speed = 4;
    private $horns = 8;
    private $toughness = 8;

    protected function setName()
    {
        return "Quicky";
    }
    protected function setSpeed()
    {
        return 1;
    }
    protected function setHorns()
    {
        return 9;
    }
    protected function setToughness()
    {
        return 10;
    }

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
    }

}
