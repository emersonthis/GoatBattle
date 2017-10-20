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

    public function testConstruct()
    {
        $quicky = new Quicky();
        $this->assertEquals(10, $quicky->speed = 10);
        $this->assertEquals(5, $quicky->horns = 5);
        $this->assertEquals(5, $quicky->toughness = 5);
    }

    public function testAction()
    {
        $location = new GoatLocation('RED');
        $anotherLocation = new GoatLocation('BLUE');
        $quicky = new Quicky($location);
        $actions = $quicky->action($location, $anotherLocation);
        
        // $this->assertEquals(2, count($actions));
        $this->assertInstanceOf(Action::class, $actions[0]);
        $this->assertTrue($actions[0]->isTurn());
        $this->assertEquals($actions[0]->measure, 1);
        $this->assertInstanceOf(Action::class, $actions[1]);
        $this->assertTrue($actions[1]->isMove());
        $this->assertEquals($actions[1]->measure, 100);
    }

    public function testTurnTo()
    {
        $location = new GoatLocation('BLUE');
        $quicky = new Quicky($location);
        $action = $quicky->turnTo(90, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(3, $action->measure);

        $action = $quicky->turnTo(180, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(-3, $action->measure);

        $action = $quicky->turnTo(270, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(-1, $action->measure);

        $action = $quicky->turnTo(360, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(1, $action->measure);

        $action = $quicky->turnTo(0, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(1, $action->measure);
    }

    // public function testTurnToFaceAndAdvance()
    // {
    //     $location = new GoatLocation('RED');
    //     $anotherLocation = new GoatLocation('BLUE');
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
