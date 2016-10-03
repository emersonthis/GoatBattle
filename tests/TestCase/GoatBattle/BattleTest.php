<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Battle;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\Faily;
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

        $this->assertTrue($battle->goat1 instanceof Goat);
        $this->assertTrue($battle->goat2 instanceof Goat);
    }
}
