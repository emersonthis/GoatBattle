<?php

namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Pokey;
use GoatBattle\Quicky;
use GoatBattle\Round;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class RoundTest extends TestCase
{

    /**
     * Runs before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_info = [
            'number' => 6,
            'redGoat' => new Quicky(),
            'blueGoat' => new Pokey()
        ];
    }

    /**
     * Runs after each test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->_info);
    }

    /**
     * @test
     * @return void
     */
    public function constructTest()
    {
        $round = new Round($this->_info);
        $this->assertEquals(6, $round->number);
    }
}
