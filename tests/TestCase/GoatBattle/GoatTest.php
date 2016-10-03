<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Stilly;
use App\Test\TestCase\GoatBattle\Faily;
use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class GoatTest extends TestCase
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
    public function validateAttributesTest()
    {
        $goatLocation = new GoatLocation();
        $goat = new Stilly($goatLocation);
        $result = $goat->validateAttributes();
        $this->assertTrue($result);

        $goat2 = new Faily1($goatLocation); # sum of attributes too high
        $result = $goat2->validateAttributes();
        $this->assertFalse($result);
    }
}
