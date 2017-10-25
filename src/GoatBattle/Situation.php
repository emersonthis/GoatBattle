<?php

namespace App\GoatBattle;

class Situation
{
    public $redGoat;
    public $blueGoat;
    public $redGoatLocation;
    public $blueGoatLocation;

    /**
     */
    public function __construct($data = [])
    {
        //@TODO Throw error if anythign missing
        $this->redGoat = clone $data['redGoat'];
        $this->blueGoat = clone $data['blueGoat'];
        $this->redGoatLocation = clone $data['redGoatLocation'];
        $this->blueGoatLocation = clone $data['blueGoatLocation'];
    }
}
