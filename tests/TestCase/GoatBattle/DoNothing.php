<?php
namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Goat;
use GoatBattle\Location;

class DoNothing extends Goat
{
    public $name = "DoNothing";
    public $speed = 2;
    public $horns = 2;
    public $toughness = 2;

    public function action(Location $myLocation, Location $opponentLocation)
    {
        $actions = [];
        return $actions;
    }
}
