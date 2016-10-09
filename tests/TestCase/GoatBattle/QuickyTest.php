<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
// use App\GoatBattle\Stilly;
// use App\Test\TestCase\GoatBattle\Faily;
// use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class QuickyTest extends TestCase
{
    public function testAction()
    {
        $location = new GoatLocation('BLUE');
        $anotherLocation = new GoatLocation('RED');
        $quicky = new Quicky($location);
        $actions = $quicky->action($location, $anotherLocation);
        $this->assertInstanceOf(Action::class, $actions[0]);

        // $location = new GoatLocation();
        // $location->x = 0;
        // $location->y = 0;
        // $quicky = new Quicky($location);
        $actions = $quicky->action($location, $anotherLocation);
        $this->assertNotEmpty($actions);
    }
}
