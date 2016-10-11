<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
// use App\GoatBattle\Stilly;
// use App\Test\TestCase\GoatBattle\Faily;
// use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class GoatLocationTest extends TestCase
{
    public function testConstruct()
    {
        $red = new GoatLocation('RED');
        $blue = new GoatLocation('BLUE');

        $this->assertEquals(-50, $red->x);
        $this->assertEquals(50, $red->y);
        $this->assertEquals(50, $blue->x);
        $this->assertEquals(-50, $blue->y);
    }

    public function testFacing()
    {
        $l1 = new GoatLocation('RED');
        $this->assertEquals($l1->facing(), 'Southeast');
        $l2 = new GoatLocation('BLUE');
        $this->assertEquals($l2->facing(), 'Northwest');
        $l3 = new GoatLocation();
        $l3->direction = 45;
        $this->assertEquals($l3->facing(), 'Northeast');
        $l3->direction = 225;
        $this->assertEquals($l3->facing(), 'Southwest');
    }

    public function testDescribe()
    {
        $loc = new GoatLocation();
        $loc->x = -50;
        $loc->y = 41;
        $loc->direction = 180;

        $description = $loc->describe();
        $this->assertEquals("@ -50,41 facing South", $description);
    }
}
