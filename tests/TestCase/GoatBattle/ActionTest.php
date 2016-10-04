<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class ActionTest extends TestCase
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
        // $goatLocation = new GoatLocation();
        // $goat = new Stilly($goatLocation);
        // $result = $goat->validateAttributes();
        // $this->assertTrue($result);

        // $goat2 = new Faily1($goatLocation); # sum of attributes too high
        // $result = $goat2->validateAttributes();
        // $this->assertFalse($result);

        $action = new Action('MOVE', 7);
        $this->assertEquals(1, $action->type);
        $this->assertEquals(7, $action->measure);

        $action = new Action('TURN', 7);
        $this->assertEquals(2, $action->type);
        $this->assertEquals(7, $action->measure);
    }

    /**
     * @test
     * @return void
     */
    public function resultTest()
    {
        $redLocation = new GoatLocation('RED');
        $redGoat = new Stilly();
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Stilly();
        $action = new Action('MOVE', 2);
        $result = $action->result($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertArrayHasKey('activeGoat', $result);
        $this->assertArrayHasKey('activeGoatLocation', $result);
        $this->assertArrayHasKey('otherGoat', $result);
        $this->assertArrayHasKey('otherGoatLocation', $result);
        $this->assertEquals(-48, $result['activeGoatLocation']->x); //@TODO Change these magic numbers to use the class properties for max/min x/y
        $this->assertEquals(48, $result['activeGoatLocation']->y);

        $action = new Action('MOVE', 4);
        $result = $action->result($blueGoat, $blueLocation, $redGoat, $redLocation);
        $this->assertEquals(46, $result['activeGoatLocation']->x);
        $this->assertEquals(-46, $result['activeGoatLocation']->y);

        //@TODO Tests for other directions!

        //@TODO Tests for the TURN action!
    }
}
