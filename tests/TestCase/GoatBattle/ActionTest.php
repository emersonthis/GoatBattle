<?php

namespace App\Test\TestCase\GoatBattle;

use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;
use GoatBattle\Action;
use GoatBattle\Bruzy;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Pokey;
use GoatBattle\Quicky;
use GoatBattle\Situation;

class ActionTest extends TestCase
{

    public $situationA; #red in center. blue in bluecorner

    /**
     * Runs before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $redGoat = new Pokey();
        $redGoat->color = 'RED';
        $blueGoat = new Quicky();
        $blueGoat->color = 'BLUE';
        $redLocation = new Location('RED');
        $redLocation->x = 0;
        $redLocation->y = 0;
        $blueLocation = new Location('BLUE');
        $this->situationA = new Situation([
            'redGoat' => $redGoat,
            'blueGoat' => $blueGoat,
            'redLocation' => $redLocation,
            'blueLocation' => $blueLocation
        ]);
    }

    /**
     * Runs after each test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->situationA);
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
        $redLocation = new Location('RED');
        $redGoat = new Pokey();
        $redGoat->color = 'RED';
        $blueLocation = new Location('BLUE');
        $blueGoat = new Pokey();
        $blueGoat->color = 'BLUE';

        $situation1 = new Situation([
            'redGoat' => $redGoat,
            'blueGoat' => $blueGoat,
            'redLocation' => $redLocation,
            'blueLocation' => $blueLocation
        ]);
        
        $action = new Action('MOVE', 2);
        $situation2 = $action->apply($redGoat, $situation1);

        $this->assertInstanceOf(Situation::class, $situation2);
        $this->assertEquals(48, $situation2->redLocation->y);
        $this->assertEquals(-48, $situation2->redLocation->x);
        $this->assertEquals(315, $situation2->redLocation->direction);
        # no change
        $this->assertEquals($situation2->blueLocation->x, 50);
        $this->assertEquals($situation2->blueLocation->y, -50);

        $action = new Action('TURN', 1);
        $situation3 = $action->apply($redGoat, $situation2);

        $this->assertInstanceOf(Situation::class, $situation3);
        $this->assertEquals(48, $situation3->redLocation->y);
        $this->assertEquals(-48, $situation3->redLocation->x);
        $this->assertEquals(360, $situation3->redLocation->direction);
        # no change
        $this->assertEquals($situation3->blueLocation->x, 50);
        $this->assertEquals($situation3->blueLocation->y, -50);

        $action1 = $redGoat->turn(1);
        $situation4 = $action1->apply($redGoat, $situation1);

        $this->assertEquals(360, $situation4->redLocation->direction);
        $this->assertEquals(-48, $situation4->redLocation->x);
        $this->assertEquals(48, $situation4->redLocation->y);
        # no change
        $this->assertEquals($situation4->blueLocation->x, 50);
        $this->assertEquals($situation4->blueLocation->y, -50);

        $action2 = $redGoat->move(9);
        $situation5 = $action2->apply($redGoat, $situation4);
        $this->assertEquals(360, $situation5->redLocation->direction);
        $this->assertEquals(-39, $situation5->redLocation->x);
        $this->assertEquals(48, $situation5->redLocation->y);
        # no change
        $this->assertEquals($situation5->blueLocation->x, 50);
        $this->assertEquals($situation5->blueLocation->y, -50);

        // Test for "ghosting northward"
        $nSituation = clone $this->situationA;
        $nSituation->redLocation->direction = 90;
        $nSituation->blueLocation->x = 0;
        $nSituation->blueLocation->y = 4;
        $action = new Action('MOVE', 5);
        $situation7 = $action->apply($redGoat, $nSituation);
        $this->assertEquals(3, $situation7->redLocation->y);
        $this->assertEquals(0, $situation7->redLocation->x);
        # no change
        $this->assertEquals($nSituation->blueLocation->x, $situation7->blueLocation->x);
        $this->assertEquals($nSituation->blueLocation->y, $situation7->blueLocation->y);

        // Test for "ghosting" northeast
        $neSituation = clone $this->situationA;
        $neSituation->redLocation->direction = 45;
        $neSituation->blueLocation->x = 5;
        $neSituation->blueLocation->y = 5;
        $action = new Action('MOVE', 8);
        $afterNeSituation = $action->apply($redGoat, $neSituation);
        $this->assertEquals(4, $afterNeSituation->redLocation->y);
        $this->assertEquals(4, $afterNeSituation->redLocation->x);

        // Test for "ghosting" east
        $eastSituation = clone $this->situationA;
        $eastSituation->redLocation->direction = 0;
        $eastSituation->blueLocation->x = 1;
        $eastSituation->blueLocation->y = 0;
        $action = new Action('MOVE', 8);
        $afterEastSituation = $action->apply($redGoat, $eastSituation);
        $this->assertEquals(0, $afterEastSituation->redLocation->x);

        // Test for "ghosting" southeast
        $southeastSituation = clone $this->situationA;
        $southeastSituation->redLocation->direction = 315;
        $southeastSituation->blueLocation->x = 5;
        $southeastSituation->blueLocation->y = -5;
        $action = new Action('MOVE', 8);
        $redGoat = new Quicky();
        $redGoat->color = 'RED';
        $afterSoutheastSituation = $action->apply($redGoat, $southeastSituation);
        $this->assertEquals(4, $afterSoutheastSituation->redLocation->x); //8
        $this->assertEquals(-4, $afterSoutheastSituation->redLocation->y);

        // // Test for "ghosting southward"
        $southSituation = clone $this->situationA;
        $southSituation->redLocation->direction = 270;
        $southSituation->blueLocation->x = 0;
        $southSituation->blueLocation->y = -5;
        $action = new Action('MOVE', 5);
        $redGoat = new Quicky();
        $redGoat->color = 'RED';
        $afterSouthSituation = $action->apply($redGoat, $southSituation);
        $this->assertEquals(0, $afterSouthSituation->redLocation->x);
        $this->assertEquals(-4, $afterSouthSituation->redLocation->y);

        // // Test for "ghosting" southwest
        $situationSouthwest = clone $this->situationA;
        $situationSouthwest->redLocation->direction = 225;
        $situationSouthwest->blueLocation->x = -3;
        $situationSouthwest->blueLocation->y = -3;
        $action = new Action('MOVE', 8);
        $afterSituationSouthwest = $action->apply($redGoat, $situationSouthwest);
        $this->assertEquals(-2, $afterSituationSouthwest->redLocation->y);
        $this->assertEquals(-2, $afterSituationSouthwest->redLocation->x);

        // Test for "ghosting" west
        $westSituation = clone $this->situationA;
        $westSituation->blueLocation->x = -4;
        $westSituation->blueLocation->y = 0;
        $westSituation->redLocation->direction = 180;
        $action = new Action('MOVE', 8);
        $redGoat = new Quicky();
        $redGoat->color = 'RED';
        $afterWestSituation = $action->apply($redGoat, $westSituation);
        $this->assertEquals(-3, $afterWestSituation->redLocation->x);

        // // Test for "ghosting" northwest
        $nwSituation = clone $this->situationA;
        $nwSituation->redLocation->direction = 135;
        $nwSituation->blueLocation->x = -4;
        $nwSituation->blueLocation->y = 4;
        $action = new Action('MOVE', 8);
        $afterNwSituation = $action->apply($redGoat, $nwSituation);
        $this->assertEquals(-3, $afterNwSituation->redLocation->x);
        $this->assertEquals(3, $afterNwSituation->redLocation->y);
    }

    public function testTurn()
    {
        # zero turn
        $redLocation = new Location('RED');
        $redGoat = new Pokey($redLocation);
        $redGoat->color = 'RED';
        $blueLocation = new Location('BLUE');
        $blueGoat = new Quicky($blueLocation);
        $blueGoat->color = 'BLUE';
        $situation1 = new Situation([
            'redGoat' => $redGoat,
            'blueGoat' => $blueGoat,
            'redLocation' => $redLocation,
            'blueLocation' => $blueLocation
        ]);
        $action = new Action('TURN', 0);
        $situationB = $action->apply($redGoat, $situation1);
        $this->assertEquals(315, $situationB->redLocation->direction);

        $action = new Action('TURN', 1);
        $situationC = $action->apply($blueGoat, $situationB);
        $this->assertEquals(180, $situationC->blueLocation->direction);
    }

    public function testRamGoat()
    {
        // East
        $redLocation = new Location();
        $redLocation->x = 45;
        $redLocation->y = 45;
        $redLocation->direction = 0;
        $redGoat = new Quicky();
        $redGoat->color = 'RED';
        $blueLocation = new Location();
        $blueLocation->x = 46;
        $blueLocation->y = 45;
        $blueLocation->direction = 270;
        $blueGoat = new Quicky();
        $blueGoat->color = 'BLUE';

        $situationA = new Situation([
            'redGoat' => $redGoat,
            'blueGoat' => $blueGoat,
            'blueLocation' => $blueLocation,
            'redLocation' => $redLocation
        ]);
        $action = new Action('RAM');
        $situationB = $action->apply($redGoat, $situationA);
        $this->assertEquals(45, $situationB->redLocation->x);
        $this->assertEquals(45, $situationB->redLocation->y);
        $this->assertEquals(0, $situationB->blueGoat->health);

        //@TODO FINISH UPDATING THESE TO PASS Situation s to action()

        // // SE
        // $redLocation->direction = 315;
        // $blueLocation->x = 46;
        // $blueLocation->y = 44;
        // $blueGoat = new Quicky($blueLocation);
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(0, $blueGoat->health);

        // // S
        // $redLocation->direction = 270;
        // $blueLocation->x = 45;
        // $blueLocation->y = 44;
        // $blueGoat = new Quicky($blueLocation);
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(0, $blueGoat->health);

        // // SW
        // $redLocation->direction = 225;
        // $blueLocation->x = 44;
        // $blueLocation->y = 44;
        // $blueGoat = new Quicky($blueLocation);
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(0, $blueGoat->health);

        // // W
        // $redLocation->direction = 180;
        // $blueLocation->x = 44;
        // $blueLocation->y = 45;
        // $blueGoat = new Quicky($blueLocation);
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(0, $blueGoat->health);

        // // NW
        // $redLocation->direction = 135;
        // $blueLocation->x = 44;
        // $blueLocation->y = 46;
        // $blueGoat = new Quicky($blueLocation);
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(0, $blueGoat->health);

        // // N
        // $redLocation->direction = 90;
        // $blueLocation->x = 45;
        // $blueLocation->y = 46;
        // $blueGoat = new Quicky($blueLocation);
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(0, $blueGoat->health);

        // // NE
        // $redLocation->direction = 45;
        // $blueLocation->x = 46;
        // $blueLocation->y = 46;
        // $blueGoat->toughness = 6;
        // $blueGoat->health = 6;
        // $endLocation = $action->apply($redGoat, $redLocation, $blueGoat, $blueLocation);
        // $this->assertEquals(1, $blueGoat->health);
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

    public function testMove()
    {
        $situation1 = new Situation([
            'redGoat' => new Quicky('RED'),
            'blueGoat' => new Bruzy('BLUE'),
            'redLocation' => new Location(['x' => -50, 'y' => -49, 'direction' => 270]),
            'blueLocation' => new Location(['x' => -50, 'y' => -50, 'direction' => 135])
        ]);

        $action = new Action('MOVE', 4);
        $situation2 = $action->apply($situation1->blueGoat, $situation1);

        $this->assertEquals(-50, $situation2->blueLocation->x);
        $this->assertEquals(-50, $situation2->blueLocation->y);
    }
}
