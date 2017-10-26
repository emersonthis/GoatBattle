<?php

namespace GoatBattle;

class Situation
{
    public $redGoat;
    public $blueGoat;
    public $redLocation;
    public $blueLocation;

    /**
     */
    public function __construct($data = [])
    {
        //@TODO Throw error if anythign missing
        $this->redGoat = clone $data['redGoat'];
        $this->blueGoat = clone $data['blueGoat'];
        $this->redLocation = clone $data['redLocation'];
        $this->blueLocation = clone $data['blueLocation'];
    }
}
