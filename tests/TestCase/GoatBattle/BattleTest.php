<?php

namespace App\Test\TestCase\GoatBattle;

use App\Test\TestCase\GoatBattle\DoNothing;
use App\Test\TestCase\GoatBattle\Faily1;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;
use GoatBattle\Action;
use GoatBattle\Battle;
use GoatBattle\Bruzy;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Pokey;
use GoatBattle\Quicky;
use GoatBattle\Round;
use GoatBattle\Situation;

class BattleTest extends TestCase
{

    public $startSituation;

    /**
     * Runs before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->startSituation = new Situation([
            'redGoat' => new Pokey('RED'),
            'redLocation' => new Location('RED'),
            'blueGoat' => new Pokey('BLUE'),
            'blueLocation' => new Location('BLUE')
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
        unset($this->startSituation);
    }

    /**
     * @test
     * @return void
     */
    public function constructTest()
    {
        $goatLocation1 = new Location();
        $goat1 = new Pokey($goatLocation1);

        $goatLocation2 = new Location();
        $goat2 = new Pokey($goatLocation2);

        $battle = new Battle($goat1, $goat2);

        $this->assertTrue($battle->goat1 instanceof Goat); //assertInstanceOf()
        $this->assertTrue($battle->goat1Location instanceof Location);
        $this->assertTrue($battle->goat2 instanceof Goat);
        $this->assertTrue($battle->goat2Location instanceof Location);
    }

    /**
     * @test
     * @expectedException Exception
     * @return void
     */
    public function constructValidatesGoat()
    {
        $goatLocation1 = new Location();
        $goat1 = new Pokey($goatLocation1);

        $goatLocation2 = new Location();
        $goat2 = new Faily1($goatLocation2);

        $battle = new Battle($goat1, $goat2);
    }

    /**
     * @test
     * @return void
     */
    public function testGo()
    {
        $goatLocation1 = new Location();
        $goat1 = new DoNothing($goatLocation1);
        $goatLocation2 = new Location();
        $goat2 = new DoNothing($goatLocation2);
        $battle = new Battle($goat1, $goat2);
        $battle->go(); //we know these two goats will time out
        $this->assertEquals($battle->maxRounds, $battle->roundCount);

        # We know that  Pokey will start with a 0 turn, which should not change it's location
        $goat1 = new Pokey();
        $goat1Location = new Location('RED');
        $goat2 = new Quicky();
        $goat2Location = new Location('BLUE');
        $battle = new Battle($goat1, $goat2);

        $situation = new Situation([
            'redGoat' => $goat1,
            'blueGoat' => $goat2,
            'redLocation' => $goat1Location,
            'blueLocation' => $goat2Location
        ]);
        $goat1Actions = $battle->takeTurn(
            $goat1,
            $situation
        );
        $this->assertEquals(-50, $goat1Actions[0]->endSituation->redLocation->x);
        $this->assertEquals(50, $goat1Actions[0]->endSituation->redLocation->y);
    }

    /**
     * @test
     * @return void
     */
    public function testGameOn()
    {
        $goatLocation1 = new Location();
        $goat1 = new Pokey($goatLocation1);
        $goatLocation2 = new Location();
        $goat2 = new Pokey($goatLocation2);

        $battle = new Battle($goat1, $goat2);

        $battle->currentSituation = new Situation([
            'redGoat' => $goat1,
            'blueGoat' => $goat2,
            'redLocation' => $goatLocation1,
            'blueLocation' => $goatLocation2
        ]);

        $this->assertTrue($battle->gameOn());

        $battle->roundCount = $battle->maxRounds;
        $this->assertFalse($battle->gameOn());

        $battle->roundCount = $battle->maxRounds - 1;
        $this->assertTrue($battle->gameOn());

        $battle->currentSituation->blueGoat->health = 0;

        $this->assertFalse($battle->gameOn());
    }

    /**
     * @test
     * @return void
     */
    public function testGetGoatActions()
    {
        $goat1 = new Pokey();
        $goat2 = new Pokey();
        $battle = new Battle($goat1, $goat2);
        $actions = $battle->getGoatActions($battle->goat1, $this->startSituation);
        $this->assertTrue(is_array($actions));
        $this->assertInstanceOf(Action::class, $actions[0]);

        $actions = $battle->getGoatActions($battle->goat2, $this->startSituation);
        $this->assertTrue(is_array($actions));
        $this->assertInstanceOf(Action::class, $actions[0]);
    }

    public function testAthorizeActions()
    {
        $goat1 = new Quicky(); //speed = 10, toughness = 5
        $goat1Location = new Location('RED');
        $goat2 = new Pokey();
        $goat2Location = new Location('BLUE');
        $situation = new Situation([
            'redGoat' => $goat1,
            'blueGoat' => $goat2,
            'redLocation' => $goat1Location,
            'blueLocation' => $goat2Location
        ]);
        $battle = new Battle($goat1, $goat2);
        $battle->currentSituation = $situation;
        
        $action1 = new Action('MOVE', 3);
        $action2 = new Action('TURN', 15);
        $goatActions = [$action1, $action2];
        $realActions = $battle->authorizeActions($goat1, $goatActions);

        $this->assertInstanceOf(Action::class, $realActions[0]);
        $this->assertEquals(3, $realActions[0]->measure);
        $this->assertTrue($realActions[0]->isMove());
        $this->assertInstanceOf(Action::class, $realActions[1]);
        $this->assertEquals(7, $realActions[1]->measure);
        $this->assertTrue($realActions[1]->isTurn());
    }

    /**
     *
     */
    public function testTakeTurn()
    {
        $goat1 = new Pokey(); //speed = 4, toughness = 8
        $goat2 = new Pokey();
        $battle = new Battle($goat1, $goat2);
        $return = $battle->takeTurn($battle->goat1, $this->startSituation);
        $this->assertTrue(is_array($return));
        $this->assertInstanceOf(Action::class, $return[0]);
    }

    public function testTrimTurn()
    {
        $goat1Location = new Location('RED');
        $goat1 = new Pokey($goat1Location); //speed = 4, toughness = 8
        $goat2Location = new Location('BLUE');
        $goat2 = new Pokey($goat2Location);
        $battle = new Battle($goat1, $goat2);

        $action = $goat1->turn(8);
        $battle->trimTurn($action, 2);
        $this->assertEquals(2, $action->measure);

        $action = $goat1->turn(-8);
        $battle->trimTurn($action, 2);
        $this->assertEquals(-2, $action->measure);

        $action = $goat1->turn(80);
        $battle->trimTurn($action, 1);
        $this->assertEquals(1, $action->measure);

        $action = $goat1->turn(0);
        $battle->trimTurn($action, 1);
        $this->assertEquals(0, $action->measure);
    }

    public function testTranscriptStartsWithFullHealth()
    {
        $goat1Location = new Location('RED');
        $goat1 = new Pokey($goat1Location); //speed = 4, toughness = 8
        $goat1Health = $goat1->health;
        $goat2Location = new Location('BLUE');
        $goat2 = new Quicky($goat2Location);
        $goat2Health = $goat2->health;
        $battle = new Battle($goat1, $goat2);
        $battle->go();

        $this->assertEquals($goat1Health, $battle->battleTranscript[0]->redGoatActions[0]->startSituation->redGoat->health);
        $this->assertEquals($goat2Health, $battle->battleTranscript[0]->redGoatActions[0]->startSituation->blueGoat->health);
    }

    public function testQuickyBruzy1()
    {
        $redGoat = new Quicky('RED');
        $blueGoat = new Bruzy('BLUE');
        // $redLocation = new Location(['x' => -50, 'y' => 50, 'direction' => 270]);
        // $blueLocation = new Location(['x' => 50, 'y' => -50, 'direction' => 135]);
        $redLocation = new Location('RED');
        $blueLocation = new Location('BLUE');

        $startSituation = new Situation([
            'redGoat' => $redGoat,
            'blueGoat' => $blueGoat,
            'redLocation' => $redLocation,
            'blueLocation' => $blueLocation
        ]);
        $action1 = new Action('TURN', -1);
        $action2 = new Action('MOVE', 9);
        $endSituation1 = $action1->apply($redGoat, $startSituation);
        $endSituation2 = $action2->apply($redGoat, $endSituation1);
        $this->assertEquals(-50, $endSituation2->redLocation->x);
        $this->assertEquals(41, $endSituation2->redLocation->y);
    }

    public function testQuickyBruzy2()
    {
        $battle = new Battle(new Quicky(), new Bruzy());
        $battle->roundCount++;
        $newRound = new Round(
            [
                'number' => $battle->roundCount,
            ]
        );

        $goat1Actions = $battle->takeTurn($battle->goat1, $battle->currentSituation);
        $newRound->redGoatActions = $goat1Actions;
        $this->assertEquals(-50, end($goat1Actions)->endSituation->redLocation->x);
        $this->assertEquals(41, end($goat1Actions)->endSituation->redLocation->y);

        # with this removed the transcripts are correct
        $goat2Actions = $battle->takeTurn($battle->goat2, $battle->currentSituation);
        $newRound->blueGoatActions = $goat2Actions;

        $this->assertEquals(-50, end($goat1Actions)->endSituation->redLocation->x);
        $this->assertEquals(41, end($goat1Actions)->endSituation->redLocation->y);
        $this->assertEquals(-50, $battle->currentSituation->redLocation->x);
        $this->assertEquals(41, $battle->currentSituation->redLocation->y);

        $battle->battleTranscript[] = $newRound;

        $this->assertEquals(-50, end($battle->battleTranscript[0]->redGoatActions)->endSituation->redLocation->x);
        $this->assertEquals(41, end($battle->battleTranscript[0]->redGoatActions)->endSituation->redLocation->y);
    }

    // # FOUND IT!!
    // public function testQuickyBruzy3()
    // {
    //     $battle = new Battle(new Quicky(), new Bruzy());
    //     $battle->go();
    //     $this->assertEquals(-50, end($battle->battleTranscript[0]->redGoatActions)->endSituation->redLocation->x);
    //     $this->assertEquals(41, end($battle->battleTranscript[0]->redGoatActions)->endSituation->redLocation->y);
    // }

    public function testHealthIncreasingBug()
    {
        # in the 10th round the red goat has health = 3
        $battle = new Battle(new Quicky(), new Bruzy());
        $battle->roundCount = 10;
        $battle->currentSituation = new Situation([
            'redGoat' => new Quicky('RED'),
            'blueGoat' => new Bruzy('BLUE'),
            'redLocation' => new Location(['x' => -50, 'y' => -49, 'direction' => 270]),
            'blueLocation' => new Location(['x' => -50, 'y' => -46, 'direction' => 315])
        ]);
        $battle->currentSituation->redGoat->health = 3;

        $goat1Actions = $battle->takeTurn($battle->currentSituation->redGoat, $battle->currentSituation);
        $this->assertEquals(3, $battle->currentSituation->redGoat->health);
    }
}
