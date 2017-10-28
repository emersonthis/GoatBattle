<?php

namespace App\Test\TestCase\GoatBattle;

use Cake\TestSuite\TestCase;
use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Quicky;
use GoatBattle\Situation;

class QuickyTest extends TestCase
{

    public function testConstruct()
    {
        $quicky = new Quicky();
        $this->assertEquals(10, $quicky->speed = 10);
        $this->assertEquals(5, $quicky->horns = 5);
        $this->assertEquals(5, $quicky->toughness = 5);
    }

    public function testAction()
    {
        $location = new Location('RED');
        $anotherLocation = new Location('BLUE');
        $quicky = new Quicky();
        $quicky->color = 'RED';
        $doNothing = new DoNothing();
        $doNothing->color = 'BLUE';
        $situation = new Situation([
            'redGoat' => $quicky,
            'redLocation' => $location,
            'blueGoat' => new $doNothing,
            'blueLocation' => new Location('BLUE')
        ]);
        $actions = $quicky->action($situation);
        
        $this->assertInstanceOf(Action::class, $actions[0]);
        $this->assertTrue($actions[0]->isTurn());
        $this->assertEquals($actions[0]->measure, -1);
        $this->assertInstanceOf(Action::class, $actions[1]);
        $this->assertTrue($actions[1]->isMove());
        $this->assertEquals($actions[1]->measure, 100);
    }

    // public function testTurnToFaceAndAdvance()
    // {
    //     $location = new Location('RED');
    //     $anotherLocation = new Location('BLUE');
    //     $quicky = new Quicky($location);
    //     $actions = $quicky->turnToFaceAndAdvance($location, $anotherLocation);

    //     $this->assertEquals(2, count($actions));
    //     $this->assertInstanceOf(Action::class, $actions[0]);
    //     $this->assertTrue($actions[0]->isTurn());
    //     $this->assertEquals($actions[0]->measure, 1);
    //     $this->assertInstanceOf(Action::class, $actions[1]);
    //     $this->assertTrue($actions[1]->isMove());
    //     $this->assertEquals($actions[1]->measure, 99);
    // }
}
