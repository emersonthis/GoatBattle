<?php
namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;

class Faily1 extends Goat
{
    public $name = "Faily";
    public $speed = 4;
    public $horns = 8;
    public $toughness = 18;

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
        $actions = [];
        return $actions;
    }
}
