<?php

namespace App\Test\TestCase\GoatBattle;

use App\GoatBattle\Action;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
use App\GoatBattle\Situation;
use Cake\TestSuite\TestCase;

class SituationTest extends TestCase
{
    public function testConstruct()
    {
        $redGoat = new Quicky();
        $initialHealth = $redGoat->health;
        $blueGoat = new Quicky();
        $redLocation = new GoatLocation('RED');
        $blueLocation = new GoatLocation('BLUE');

        $situation = new Situation([
            'redGoat' => $redGoat,
            'redGoatLocation' => $redLocation,
            'blueGoat' => $blueGoat,
            'blueGoatLocation' => $blueLocation
        ]);

        # make sure references are "detached"
        $redLocation->x = 0;
        $redLocation->y = 0;
        $this->assertEquals(-50, $situation->redGoatLocation->x);
        $redGoat->health = 0;
        $this->assertEquals($initialHealth, $situation->redGoat->health);
    }
}
