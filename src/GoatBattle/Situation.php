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
        if (empty($data['redGoat'])) {
            throw new \Exception('No redGoat passed');
        }
        if (empty($data['blueGoat'])) {
            throw new \Exception('No blueGoat passed');
        }
        if (empty($data['redLocation'])) {
            throw new \Exception('No redLocation passed');
        }
        if (empty($data['blueLocation'])) {
            throw new \Exception('No blueLocation passed');
        }
        //@TODO Throw error if anythign missing
        $this->redGoat = clone $data['redGoat'];
        $this->blueGoat = clone $data['blueGoat'];
        $this->redLocation = clone $data['redLocation'];
        $this->blueLocation = clone $data['blueLocation'];
    }

    /**
     * Override __clone() to create "deep copies" of referenced objects
     */
    public function __clone()
    {
        $this->redGoat = clone $this->redGoat;
        $this->blueGoat = clone $this->blueGoat;
        $this->redLocation = clone $this->redLocation;
        $this->blueLocation = clone $this->blueLocation;
    }
}
