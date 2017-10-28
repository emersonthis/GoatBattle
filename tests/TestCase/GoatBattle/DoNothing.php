<?php
namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Situation;

class DoNothing extends Goat
{
    public $name = "DoNothing";
    public $speed = 2;
    public $horns = 2;
    public $toughness = 2;

    public function action(Situation $situation)
    {
        $actions = [];
        return $actions;
    }
}
