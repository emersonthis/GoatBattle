<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\Faily;
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
        $result = $goat->turn(5);
        $this->assertTrue($result instanceof \App\GoatBattle\Action);
        $this->assertEquals(5, $result->measure);

        // $goat2 = new Faily1($goatLocation); # sum of attributes too high
        // $result = $goat2->validateAttributes();
        // $this->assertFalse($result);
    }

    /**
     * @test
     * @return void
     */
    public function moveTest()
    {
        $goatLocation = new GoatLocation();
        $goat = new Stilly($goatLocation);
        $result = $goat->move(5);
        $this->assertTrue($result instanceof \App\GoatBattle\Action);
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
}
