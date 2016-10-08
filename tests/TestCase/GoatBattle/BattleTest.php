<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Battle;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\Faily1;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class BattleTest extends TestCase
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
    public function constructTest()
    {
        $goatLocation1 = new GoatLocation();
        $goat1 = new Stilly($goatLocation1);

        $goatLocation2 = new GoatLocation();
        $goat2 = new Stilly($goatLocation2);

        $battle = new Battle($goat1, $goat2);

        $this->assertTrue($battle->goat1 instanceof Goat); //assertInstanceOf()
        $this->assertTrue($battle->goat1Location instanceof GoatLocation);
        $this->assertTrue($battle->goat2 instanceof Goat);
        $this->assertTrue($battle->goat2Location instanceof GoatLocation);
    }

    /**
     * @test
     * @expectedException Exception
     * @return void
     */
    public function constructValidatesGoat()
    {
        $goatLocation1 = new GoatLocation();
        $goat1 = new Stilly($goatLocation1);

        $goatLocation2 = new GoatLocation();
        $goat2 = new Faily1($goatLocation2);

        $battle = new Battle($goat1, $goat2);
    }

    /**
     * @test
     * @return void
     */
    public function testGo()
    {
        $goatLocation1 = new GoatLocation();
        $goat1 = new Stilly($goatLocation1);

        $goatLocation2 = new GoatLocation();
        $goat2 = new Stilly($goatLocation2);

        $battle = new Battle($goat1, $goat2);

        $battle->go(); //we know these two goats will time out

        $this->assertEquals($battle->maxRounds, $battle->roundCount);
    }

    /**
     * @test
     * @return void
     */
    public function testGameOn()
    {
        $goatLocation1 = new GoatLocation();
        $goat1 = new Stilly($goatLocation1);

        $goatLocation2 = new GoatLocation();
        $goat2 = new Stilly($goatLocation2);

        $battle = new Battle($goat1, $goat2);

        $this->assertTrue($battle->gameOn());

        $battle->roundCount = $battle->maxRounds;
        $this->assertFalse($battle->gameOn());

        $battle->roundCount = $battle->maxRounds - 1;
        $this->assertTrue($battle->gameOn());

        $goat2->ouch(50);

        $this->assertFalse($battle->gameOn());
    }
}
