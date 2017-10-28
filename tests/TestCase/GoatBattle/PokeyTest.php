<?php

namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Pokey;
use GoatBattle\Situation;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class PokeyTest extends TestCase
{
    public function testAction()
    {
        $location = new Location('BLUE');
        $anotherLocation = new Location('RED');
        $otherGoat = new Pokey();

        $situation = new Situation([
            'blueGoat' => $this,
            'blueLocation' => $location,
            'redGoat' => $otherGoat,
            'redLocation' => $anotherLocation
        ]);
        $stilly = new Pokey();
        $actions = $stilly->action($situation);
        $this->assertInstanceOf(Action::class, $actions[0]);
        $this->assertInstanceOf(Action::class, $actions[1]);
    }
}
