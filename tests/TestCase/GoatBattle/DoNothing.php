<?php
namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;

class DoNothing extends Goat
{
    public $name = "DoNothing";
    public $speed = 2;
    public $horns = 2;
    public $toughness = 2;

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
        $actions = [];
        return $actions;
    }
}
