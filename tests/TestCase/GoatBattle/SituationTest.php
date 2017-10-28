<?php

namespace App\Test\TestCase\GoatBattle;

use Cake\TestSuite\TestCase;
use GoatBattle\Action;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Quicky;
use GoatBattle\Situation;

class SituationTest extends TestCase
{
    public function testConstruct()
    {
        $redGoat = new Quicky();
        $initialHealth = $redGoat->health;
        $blueGoat = new Quicky();
        $redLocation = new Location('RED');
        $blueLocation = new Location('BLUE');

        $situation = new Situation([
            'redGoat' => $redGoat,
            'redLocation' => $redLocation,
            'blueGoat' => $blueGoat,
            'blueLocation' => $blueLocation
        ]);

        # make sure references are "detached"
        $redLocation->x = 0;
        $redLocation->y = 0;
        $this->assertEquals(-50, $situation->redLocation->x);
        $redGoat->health = 0;
        $this->assertEquals($initialHealth, $situation->redGoat->health);
    }

    public function testClone()
    {
        $situation = new Situation([
            'redGoat' => new Quicky(),
            'blueGoat' => new Quicky(),
            'redLocation' => new Location('RED'),
            'blueLocation' => new Location('BLUE')
        ]);
        $situationClone = clone $situation;
        $situationClone->redLocation->x = 50;
        $situationClone->redLocation->y = 48;
        $this->assertEquals(-50, $situation->redLocation->x);
        $this->assertEquals(50, $situation->redLocation->y);
    }
}
