<?php

namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Quicky;
use GoatBattle\Pokey;
use App\Test\TestCase\GoatBattle\Faily1;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class GoatTest extends TestCase
{

    /**
     * Runs before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Runs after each test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     * @return void
     */
    public function validateAttributesTest()
    {
        $goatLocation = new Location();
        $goat = new Pokey($goatLocation);
        $result = $goat->validateAttributes();
        $this->assertTrue($result);

        $goat2 = new Faily1($goatLocation); # sum of attributes too high
        $result = $goat2->validateAttributes();
        $this->assertFalse($result);
    }

    /**
     * @test
     * @return void
     */
    public function turnTest()
    {
        $goatLocation = new Location();
        $goat = new Pokey($goatLocation);
        $action = $goat->turn(5);
        $this->assertTrue($action instanceof \GoatBattle\Action);
        $this->assertEquals(5, $action->measure);
    }

    /**
     * @test
     * @return void
     */
    public function moveTest()
    {
        $goatLocation = new Location();
        $goat = new Pokey($goatLocation);
        $action = $goat->move(5);
        $this->assertTrue($action instanceof \GoatBattle\Action);
        $this->assertEquals(5, $action->measure);
    }

    /**
     * @test
     * @return void
     */
    public function testFace()
    {
        $location = new Location();
        $location->x = 0;
        $location->y = 0;
        $location->direction = 0;
        $goat = new Pokey($location);

        $action = $goat->face(0, 50, $location);

        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(-2, $action->measure);

        $action = $goat->face(50, -50, $location);
        $this->assertEquals(1, $action->measure);

        $action = $goat->face(47, -50, $location);
        $this->assertEquals(1, $action->measure);

        $action = $goat->face(-47, 2, $location);
        $this->assertEquals(4, abs($action->measure));

        //RED CORNER NOT WORKING
        $location = new Location();
        $location->x = -50;
        $location->y = 50;
        $location->direction = 315;
        $goat = new Pokey($location);
        $action = $goat->face(0, 0, $location);
        $this->assertEquals(0, $action->measure);

        //BLUE CORNER NOT WORKING
        $location = new Location();
        $location->x = 50;
        $location->y = -50;
        $location->direction = 135;
        $goat = new Pokey($location);
        $action = $goat->face(0, 0, $location);
        $this->assertEquals(0, $action->measure);
    }

    public function testTurnTo()
    {
        $location = new Location('BLUE');
        $quicky = new Quicky($location);
        $action = $quicky->turnTo(0, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(-3, $action->measure);

        $action = $quicky->turnTo(180, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(1, $action->measure);

        $action = $quicky->turnTo(270, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(3, $action->measure);

        $action = $quicky->turnTo(360, $location);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals(-3, $action->measure);
    }
}
