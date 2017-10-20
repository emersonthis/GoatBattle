<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
use App\GoatBattle\Stilly;
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
        $goatLocation = new GoatLocation();
        $goat = new Stilly($goatLocation);
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
        $goatLocation = new GoatLocation();
        $goat = new Stilly($goatLocation);
        $action = $goat->turn(5);
        $this->assertTrue($action instanceof \App\GoatBattle\Action);
        $this->assertEquals(5, $action->measure);
    }

    /**
     * @test
     * @return void
     */
    public function moveTest()
    {
        $goatLocation = new GoatLocation();
        $goat = new Stilly($goatLocation);
        $action = $goat->move(5);
        $this->assertTrue($action instanceof \App\GoatBattle\Action);
        $this->assertEquals(5, $action->measure);
    }

    /**
     * @test
     * @return void
     */
    public function testOuch()
    {
        $goatLocation = new GoatLocation();
        $goat = new Stilly($goatLocation);

        $startToughness = $goat->toughness();

        $endToughness = $goat->ouch(5);

        $this->assertEquals($startToughness - 5, $endToughness);
    }

    /**
     * @test
     * @return void
     */
    public function testFace()
    {
        $location = new GoatLocation();
        $location->x = 0;
        $location->y = 0;
        $location->direction = 90;
        $goat = new Stilly($location);

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
        $location = new GoatLocation();
        $location->x = -50;
        $location->y = 50;
        $location->direction = 135;
        $goat = new Stilly($location);
        $action = $goat->face(0, 0, $location);
        $this->assertEquals(0, $action->measure);

        //BLUE CORNER NOT WORKING
        $location = new GoatLocation();
        $location->x = 50;
        $location->y = -50;
        $location->direction = 315;
        $goat = new Stilly($location);
        $action = $goat->face(0, 0, $location);
        $this->assertEquals(0, $action->measure);
    }
}
