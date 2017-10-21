<?php
namespace App\GoatBattle;

// use Cake\Log\Log;

class Round
{
    public $number;
    public $redGoat;
    public $blueGoat;
    public $redGoatStartLocation;
    public $blueGoatStartLocation;
    public $redGoatActions;
    public $blueGoatActions;
    public $redGoatEndLocation;
    public $blueGoatEndLocation;

    /**
     */
    public function __construct($info = [])
    {
        $this->number = (!empty($info['number'])) ? $info['number'] : null;
        $this->redGoat = (!empty($info['redGoat'])) ? $info['redGoat'] : null;
        $this->blueGoat = (!empty($info['blueGoat'])) ? $info['blueGoat'] : null;
        $this->redGoatStartLocation = (!empty($info['redGoatStartLocation'])) ? $info['redGoatStartLocation'] : null;
        $this->blueGoatStartLocation = (!empty($info['blueGoatStartLocation'])) ? $info['blueGoatStartLocation'] : null;
        $this->redGoatActions = (!empty($info['redGoatActions'])) ? $info['redGoatActions'] : null;
        $this->blueGoatActions = (!empty($info['blueGoatActions'])) ? $info['blueGoatActions'] : null;
        $this->redGoatEndLocation = (!empty($info['redGoatEndLocation'])) ? $info['redGoatEndLocation'] : null;
        $this->blueGoatEndLocation = (!empty($info['blueGoatEndLocation'])) ? $info['blueGoatEndLocation'] : null;
    }
}
