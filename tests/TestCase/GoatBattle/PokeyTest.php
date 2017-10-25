<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\ Pokey;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class PokeyTest extends TestCase
{
    public function testAction()
    {
        $location = new GoatLocation('BLUE');
        $anotherLocation = new GoatLocation('RED');
        $stilly = new Pokey($location);
        $actions = $stilly->action($location, $anotherLocation);
        $this->assertInstanceOf(Action::class, $actions[0]);
        $this->assertInstanceOf(Action::class, $actions[1]);
    }
}