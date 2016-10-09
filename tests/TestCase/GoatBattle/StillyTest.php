<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class StillyTest extends TestCase
{
    public function testAction()
    {
        $location = new GoatLocation('BLUE');
        $anotherLocation = new GoatLocation('RED');
        $stilly = new Stilly($location);
        $actions = $stilly->action($location, $anotherLocation);
        $this->assertInstanceOf(Action::class, $actions[0]);

        $location = new GoatLocation();
        $location->x = 0;
        $location->y = 0;
        $stilly = new Stilly($location);
        $actions = $stilly->action($location, $anotherLocation);
        $this->assertEmpty($actions);
    }
}
