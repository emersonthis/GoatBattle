<?php
namespace App\GoatBattle;

use App\GoatBattle\Goat;

class Fatty extends Goat
{
    private $speed = 4;
    private $horns = 8;
    private $toughness = 8;
    public $name = "Fatty";

    public function action(GoatLocation $myLocation, GoatLocation $opponentLocation)
    {
        return [];
    }

}
