<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
use App\GoatBattle\Pokey;
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
        $action = new Action('MOVE', 7);
        $this->assertEquals(1, $action->type);
        $this->assertEquals(7, $action->measure);

        $action = new Action('TURN', 7);
        $this->assertEquals(2, $action->type);
        $this->assertEquals(7, $action->measure);
    }

    public function testApply()
    {
        $redLocation = new GoatLocation('RED');
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Pokey($blueLocation);
        
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);

        $this->assertInstanceOf(GoatLocation::class, $endLocation);
        $this->assertEquals(48, $endLocation->y);
        $this->assertEquals(-48, $endLocation->x);
        $this->assertEquals(315, $endLocation->direction);
        # no change
        $this->assertEquals($blueLocation->x, 50);
        $this->assertEquals($blueLocation->y, -50);

        $action = new Action('TURN', 1);
        $endLocation = $action->apply($redGoat, $endLocation, $blueGoat, $blueLocation);

        $this->assertInstanceOf(GoatLocation::class, $endLocation);
        $this->assertEquals(48, $endLocation->y);
        $this->assertEquals(-48, $endLocation->x);
        $this->assertEquals(360, $endLocation->direction);
        # no change
        $this->assertEquals($blueLocation->x, 50);
        $this->assertEquals($blueLocation->y, -50);

        $redLocation = new GoatLocation('RED');
        $redGoat = new Quicky($redLocation);
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Pokey($blueLocation);
        # no change
        $this->assertEquals($blueLocation->x, 50);
        $this->assertEquals($blueLocation->y, -50);

        $action1 = $redGoat->turn(1);
        $endLocation1 = $action1->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(360, $endLocation1->direction);
        $this->assertEquals(-50, $endLocation1->x);
        $this->assertEquals(50, $endLocation1->y);
        # no change
        $this->assertEquals($blueLocation->x, 50);
        $this->assertEquals($blueLocation->y, -50);

        $action2 = $redGoat->move(9);
        $endLocation2 = $action2->apply($redGoat, $endLocation1, $blueGoat, $blueLocation);
        $this->assertEquals(360, $endLocation2->direction);
        $this->assertEquals(-41, $endLocation2->x);
        $this->assertEquals(50, $endLocation2->y);
        # no change
        $this->assertEquals($blueLocation->x, 50);
        $this->assertEquals($blueLocation->y, -50);

        // Test for "ghosting northward"
        $redLocation = new GoatLocation();
        $redLocation->x = 50;
        $redLocation->y = -50;
        $redLocation->direction = 90;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = -49;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-50, $endLocation->y);
        # no change
        $this->assertEquals($blueLocation->x, 50);
        $this->assertEquals($blueLocation->y, -49);

        // Test for "ghosting" northeast
        $redLocation = new GoatLocation();
        $redLocation->x = 47;
        $redLocation->y = 47;
        $redLocation->direction = 45;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(49, $endLocation->y);

        // Test for "ghosting" east
        $redLocation = new GoatLocation();
        $redLocation->x = 44;
        $redLocation->y = 50;
        $redLocation->direction = 0;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 8);
        $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // debug($redLocation);
        // debug($action->endLocation);
        // debug($action);
        $this->assertEquals(49, $redLocation->x);

        // Test for "ghosting" southeast
        $redLocation = new GoatLocation();
        $redLocation->x = 45;
        $redLocation->y = -45;
        $redLocation->direction = 315;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = 50;
        $blueLocation->y = -50;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(49, $endLocation->x);
        $this->assertEquals(-49, $endLocation->y);


        // Test for "ghosting southward"
        $redLocation = new GoatLocation();
        $redLocation->x = -50;
        $redLocation->y = 50;
        $redLocation->direction = 270;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = 49;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(50, $endLocation->y);

        // Test for "ghosting" southwest
        $redLocation = new GoatLocation();
        $redLocation->x = -47;
        $redLocation->y = -47;
        $redLocation->direction = 225;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = -50;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-49, $endLocation->y);

        // Test for "ghosting" west
        $redLocation = new GoatLocation();
        $redLocation->x = -45;
        $redLocation->y = 50;
        $redLocation->direction = 180;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-49, $endLocation->x);

        // Test for "ghosting" northwest
        $redLocation = new GoatLocation();
        $redLocation->x = -45;
        $redLocation->y = 45;
        $redLocation->direction = 135;
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation();
        $blueLocation->x = -50;
        $blueLocation->y = 50;
        $blueLocation->direction = 270;
        $blueGoat = new Pokey($blueLocation);
        $action = new Action('MOVE', 8);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(-49, $endLocation->x);

        // Quicky vs  Pokey experiment
        $quickyLocation = new GoatLocation();
        $quickyLocation->x = -2;
        $quickyLocation->y = 0;
        $quickyLocation->direction = 360;
        $quicky = new Quicky($quickyLocation);
        $stillyLocation = new GoatLocation();
        $stillyLocation->x = 0;
        $stillyLocation->y = 0;
        $stillyLocation->direction = 0;
        $stilly = new Pokey($stillyLocation);
        $action = new Action('MOVE', 2);
        $endLocation = $action->apply($quicky, $quickyLocation, $stilly, $stillyLocation);
        $this->assertEquals(-1, $endLocation->x);
        $this->assertEquals(-1, $quickyLocation->x);
    }

    public function testTurn()
    {
        # zero turn
        $redLocation = new GoatLocation('RED');
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Quicky($blueLocation);
        $action = new Action('TURN', 0);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(315, $endLocation->direction);

        # move 3... not sure why this is in here
        $secondAction = new Action('MOVE', 3);
        $secondEndLocation = $secondAction->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(315, $secondEndLocation->direction);
        $this->assertEquals(-47, $secondEndLocation->x);
        $this->assertEquals(47, $secondEndLocation->y);

        $redLocation = new GoatLocation('RED');
        $redGoat = new Pokey($redLocation);
        $blueLocation = new GoatLocation('BLUE');
        $blueGoat = new Quicky($blueLocation);
        $action = new Action('TURN', 1);
        $endLocation = $action->apply($blueGoat, $blueLocation, $redGoat, $redLocation);
        $this->assertEquals(180, $endLocation->direction);
    }

    public function testRamGoat()
    {
        // East
        $redLocation = new GoatLocation();
        $redLocation->x = 45;
        $redLocation->y = 45;
        $redLocation->direction = 0;
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
        $this->assertEquals(0, $blueGoat->health);

        // SE
        $redLocation->direction = 315;
        $blueLocation->x = 46;
        $blueLocation->y = 44;
        $blueGoat = new Quicky($blueLocation);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->health);

        // S
        $redLocation->direction = 270;
        $blueLocation->x = 45;
        $blueLocation->y = 44;
        $blueGoat = new Quicky($blueLocation);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->health);

        // SW
        $redLocation->direction = 225;
        $blueLocation->x = 44;
        $blueLocation->y = 44;
        $blueGoat = new Quicky($blueLocation);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->health);

        // W
        $redLocation->direction = 180;
        $blueLocation->x = 44;
        $blueLocation->y = 45;
        $blueGoat = new Quicky($blueLocation);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->health);

        // NW
        $redLocation->direction = 135;
        $blueLocation->x = 44;
        $blueLocation->y = 46;
        $blueGoat = new Quicky($blueLocation);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->health);

        // N
        $redLocation->direction = 90;
        $blueLocation->x = 45;
        $blueLocation->y = 46;
        $blueGoat = new Quicky($blueLocation);
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(0, $blueGoat->health);

        // NE
        $redLocation->direction = 45;
        $blueLocation->x = 46;
        $blueLocation->y = 46;
        $blueGoat->toughness = 6;
        $blueGoat->health = 6;
        $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        $this->assertEquals(1, $blueGoat->health);
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
