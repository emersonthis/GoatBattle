<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Battle;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\DoNothing;
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
        $goat1 = new DoNothing($goatLocation1);
        $goatLocation2 = new GoatLocation();
        $goat2 = new DoNothing($goatLocation2);
        $battle = new Battle($goat1, $goat2);
        $battle->go(); //we know these two goats will time out
        $this->assertEquals($battle->maxRounds, $battle->roundCount);

        # We know that Stilly will start with a 0 turn, which should not change it's location
        $goat1 = new Stilly();
        $goat1Location = new GoatLocation('RED');
        $goat2 = new Quicky();
        $goat2Location = new GoatLocation('BLUE');
        $battle = new Battle($goat1, $goat2);
        $goat1Actions = $battle->takeTurn(
            $goat1,
            $goat1Location,
            $goat2,
            $goat2Location
        );
        $this->assertEquals(-50, $goat1Actions[0]->endSituation->redGoatLocation->x);
        $this->assertEquals(50, $goat1Actions[0]->endSituation->redGoatLocation->y);
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

        $goat2->health = 0;

        $this->assertFalse($battle->gameOn());
    }

    /**
     * @test
     * @return void
     */
    public function testGetGoatActions()
    {
        $goatLocation1 = new GoatLocation('RED');
        $goat1 = new Stilly($goatLocation1);

        $goatLocation2 = new GoatLocation('BLUE');
        $goat2 = new Stilly($goatLocation2);

        $battle = new Battle($goat1, $goat2);

        $actions = $battle->getGoatActions($battle->goat1, $goatLocation1, $goatLocation2);

        $this->assertTrue(is_array($actions));
        $this->assertInstanceOf(Action::class, $actions[0]);

        $actions = $battle->getGoatActions($battle->goat2, $goatLocation2, $goatLocation1);

        $this->assertTrue(is_array($actions));
        $this->assertInstanceOf(Action::class, $actions[0]);
    }

    public function testAthorizeActions()
    {
        $goat1 = new Quicky(); //speed = 10, toughness = 5
        $goat1Location = new GoatLocation('RED');
        $goat2 = new Stilly();
        $goat2Location = new GoatLocation('BLUE');
        $battle = new Battle($goat1, $goat2);
        
        $action1 = new Action('MOVE', 3);
        $action2 = new Action('TURN', 15);
        $goatActions = [$action1, $action2];
        $realActions = $battle->authorizeActions($goat1, $goatActions, $goat1Location, $goat2Location);

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
        $goat1Location = new GoatLocation('RED');
        $goat1 = new Stilly($goat1Location); //speed = 4, toughness = 8
        $goat2Location = new GoatLocation('BLUE');
        $goat2 = new Stilly($goat2Location);
        $battle = new Battle($goat1, $goat2);

        $return = $battle->takeTurn(
            $battle->goat1,
            $battle->goat1Location,
            $battle->goat2,
            $battle->goat2Location
        );

        $this->assertTrue(is_array($return));
        $this->assertInstanceOf(Action::class, $return[0]);
        $this->assertEquals(-49, $battle->goat1Location->x);
        $this->assertEquals(49, $battle->goat1Location->y);

        $return = $battle->takeTurn(
            $battle->goat2,
            $battle->goat2Location,
            $battle->goat1,
            $battle->goat1Location
        );

        $this->assertTrue(is_array($return));
        $this->assertInstanceOf(Action::class, $return[0]);
        $this->assertEquals(49, $battle->goat2Location->x);
        $this->assertEquals(-49, $battle->goat2Location->y);
    }

    public function testTrimTurn()
    {
        $goat1Location = new GoatLocation('RED');
        $goat1 = new Stilly($goat1Location); //speed = 4, toughness = 8
        $goat2Location = new GoatLocation('BLUE');
        $goat2 = new Stilly($goat2Location);
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
        $goat1Location = new GoatLocation('RED');
        $goat1 = new Stilly($goat1Location); //speed = 4, toughness = 8
        $goat1Health = $goat1->health;
        $goat2Location = new GoatLocation('BLUE');
        $goat2 = new Quicky($goat2Location);
        $goat2Health = $goat2->health;
        $battle = new Battle($goat1, $goat2);
        $battle->go();

        $this->assertEquals($goat1Health, $battle->battleTranscript[0]->redGoatActions[0]->startSituation->redGoat->health);
        $this->assertEquals($goat2Health, $battle->battleTranscript[0]->redGoatActions[0]->startSituation->blueGoat->health);
    }
}
