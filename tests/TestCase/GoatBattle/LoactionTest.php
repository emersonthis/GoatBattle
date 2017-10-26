<?php

namespace App\Test\TestCase\GoatBattle;

use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Quicky;
// use GoatBattle\ Pokey;
// use App\Test\TestCase\GoatBattle\Faily;
// use Cake\TestSuite\Fixture\PhpFixture;
use Cake\TestSuite\TestCase;

class LocationTest extends TestCase
{
    public function testConstruct()
    {
        $red = new Location('RED');
        $blue = new Location('BLUE');

        $this->assertEquals(-50, $red->x);
        $this->assertEquals(50, $red->y);
        $this->assertEquals(50, $blue->x);
        $this->assertEquals(-50, $blue->y);
    }

    public function testFacing()
    {
        $l1 = new Location('RED');
        $this->assertEquals($l1->facing(), 'Southeast');
        $l2 = new Location('BLUE');
        $this->assertEquals($l2->facing(), 'Northwest');
        $l3 = new Location();
        $l3->direction = 45;
        $this->assertEquals($l3->facing(), 'Northeast');
        $l3->direction = 225;
        $this->assertEquals($l3->facing(), 'Southwest');
    }

    public function testDescribe()
    {
        $loc = new Location();
        $loc->x = -50;
        $loc->y = 41;
        $loc->direction = 180;

        $description = $loc->describe();
        $this->assertEquals("@ -50,41 facing West", $description);
    }
}
