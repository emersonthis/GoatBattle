<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
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

    // /**
    //  * @test
    //  * @return void
    //  */
    // public function resultTest()
    // {
    //     $redLocation = new GoatLocation('RED');
    //     $redGoat = new Stilly();
    //     $blueLocation = new GoatLocation('BLUE');
    //     $blueGoat = new Stilly();
    //     $action = new Action('MOVE', 2);
    //     $result = $action->result($redGoat, $redLocation, $blueGoat, $blueLocation);
    //     $this->assertArrayHasKey('activeGoat', $result);
    //     $this->assertArrayHasKey('activeGoatLocation', $result);
    //     $this->assertArrayHasKey('otherGoat', $result);
    //     $this->assertArrayHasKey('otherGoatLocation', $result);
    //     $this->assertEquals(-48, $result['activeGoatLocation']->x); //@TODO Change these magic numbers to use the class properties for max/min x/y
    //     $this->assertEquals(48, $result['activeGoatLocation']->y);

    //     $action = new Action('MOVE', 4);
    //     $result = $action->result($blueGoat, $blueLocation, $redGoat, $redLocation);
    //     $this->assertEquals(46, $result['activeGoatLocation']->x);
    //     $this->assertEquals(-46, $result['activeGoatLocation']->y);

    //     //@TODO Tests for other directions!

    //     //@TODO Tests for the TURN action!
    // }

    public function testApply()
    {
        $redLocation = new GoatLocation('RED');
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Stilly($blueLocation);
        
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);

        $this->assertInstanceOf(GoatLocation::class, $endLocation);
        $this->assertEquals(48, $endLocation->y);
        $this->assertEquals(-48, $endLocation->x);
        $this->assertEquals(135, $endLocation->direction);

        $action = new Action('TURN', 1);
        $endLocation = $action->apply($redGoat, $endLocation, $blueGoat, $blueLocation);

        $this->assertInstanceOf(GoatLocation::class, $endLocation);
        $this->assertEquals(48, $endLocation->y);
        $this->assertEquals(-48, $endLocation->x);
        $this->assertEquals(180, $endLocation->direction);

        $redLocation = new GoatLocation('RED');
        $redGoat = new Quicky($redLocation);
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Stilly($blueLocation);

        $action1 = $redGoat->turn(1);
        $endLocation1 = $action1->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(180, $endLocation1->direction);
        $this->assertEquals(50, $endLocation1->y);
        $this->assertEquals(-50, $endLocation1->x);

        $action2 = $redGoat->move(9);
        $endLocation2 = $action2->apply($redGoat, $endLocation1, $blueGoat, $blueLocation);
        $this->assertEquals(180, $endLocation2->direction);
        $this->assertEquals(41, $endLocation2->y);
        $this->assertEquals(-50, $endLocation2->x);


        // Test for "ghosting northward"
        $redLocation = new GoatLocation();
        $redLocation->x = 50;
        $redLocation->y = -50;
        $redLocation->direction = 0;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = -49;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-50, $endLocation->y);

        // Test for "ghosting" northeast
        $redLocation = new GoatLocation();
        $redLocation->x = 47;
        $redLocation->y = 47;
        $redLocation->direction = 45;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(49, $endLocation->y);

        // Test for "ghosting" east
        $redLocation = new GoatLocation();
        $redLocation->x = 45;
        $redLocation->y = 50;
        $redLocation->direction = 90;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(49, $endLocation->x);

        // Test for "ghosting" southeast
        $redLocation = new GoatLocation();
        $redLocation->x = 45;
        $redLocation->y = -45;
        $redLocation->direction = 135;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = -50;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(49, $endLocation->x);
        $this->assertEquals(-49, $endLocation->y);


        // Test for "ghosting southward"
        $redLocation = new GoatLocation();
        $redLocation->x = -50;
        $redLocation->y = 50;
        $redLocation->direction = 180;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = 49;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(50, $endLocation->y);

        // Test for "ghosting" southwest
        $redLocation = new GoatLocation();
        $redLocation->x = -47;
        $redLocation->y = -47;
        $redLocation->direction = 225;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = -50;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-49, $endLocation->y);

        // Test for "ghosting" west
        $redLocation = new GoatLocation();
        $redLocation->x = -45;
        $redLocation->y = 50;
        $redLocation->direction = 270;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-49, $endLocation->x);

        // Test for "ghosting" northwest
        $redLocation = new GoatLocation();
        $redLocation->x = -45;
        $redLocation->y = 45;
        $redLocation->direction = 315;
        $redGoat = new Stilly($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Stilly($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-49, $endLocation->x);
    }

    public function testRamGoat()
    {
        // East
        $redLocation = new GoatLocation();
        $redLocation->x = 45;
        $redLocation->y = 45;
        $redLocation->direction = 90;
        $redGoat = new Quicky($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 46;
        $blueLocation->y = 45;
        $blueLocation->direction = 270;
        $blueGoat = new Quicky($blueLocation);
        $action = new Action('RAM');
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(45, $endLocation->x);
        $this->assertEquals(45, $endLocation->y);
        $this->assertEquals(0, $blueGoat->toughness);

        // SE
        $redLocation->direction = 135;
        $blueLocation->x = 46;
        $blueLocation->y = 44;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->toughness);

        // S
        $redLocation->direction = 180;
        $blueLocation->x = 45;
        $blueLocation->y = 44;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->toughness);

        // SW
        $redLocation->direction = 225;
        $blueLocation->x = 44;
        $blueLocation->y = 44;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->toughness);

        // W
        $redLocation->direction = 270;
        $blueLocation->x = 44;
        $blueLocation->y = 45;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->toughness);

        // NW
        $redLocation->direction = 315;
        $blueLocation->x = 44;
        $blueLocation->y = 46;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->toughness);

        // N
        $redLocation->direction = 0;
        $blueLocation->x = 45;
        $blueLocation->y = 46;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->toughness);

        // NE
        $redLocation->direction = 45;
        $blueLocation->x = 46;
        $blueLocation->y = 46;
        $blueGoat->toughness = 6;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(1, $blueGoat->toughness);
    }

    public function testValidateDirection()
    {
        $true = Action::validateDirection(90);
        $this->assertTrue($true);

        $false = Action::validateDirection(95);
        $this->assertFalse($false);

        $false = Action::validateDirection(null);
        $this->assertFalse($false);

        $true = Action::validateDirection(360);
        $this->assertTrue($true);
    }
}
