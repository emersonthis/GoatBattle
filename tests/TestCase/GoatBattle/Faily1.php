<?php
namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Goat;
use GoatBattle\Location;

class Faily1 extends Goat
{
    public $name = "Faily";
    public $speed = 4;
    public $horns = 8;
    public $toughness = 18;

    public function action(Location $myLocation, Location $opponentLocation)
    {
        $actions = [];
        return $actions;
    }
}
