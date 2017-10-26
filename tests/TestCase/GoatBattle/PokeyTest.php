<?php

namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Pokey;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class PokeyTest extends TestCase
{
    public function testAction()
    {
        $location = new Location('BLUE');
        $anotherLocation = new Location('RED');
        $stilly = new Pokey($location);
        $actions = $stilly->action($location, $anotherLocation);
        $this->assertInstanceOf(Action::class, $actions[0]);
        $this->assertInstanceOf(Action::class, $actions[1]);
    }
}
