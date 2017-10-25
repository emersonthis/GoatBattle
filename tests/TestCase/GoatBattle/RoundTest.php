<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
use App\GoatBattle\Round;
use App\GoatBattle\Stilly;
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
            'blueGoat' => new Stilly()
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
