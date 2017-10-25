<?php
namespace App\GoatBattle;

// use Cake\Log\Log;

class Round
{
    public $number;
    public $redGoatActions;
    public $blueGoatActions;

    /**
     */
    public function __construct($info = [])
    {
        $this->number = (!empty($info['number'])) ? $info['number'] : null;
        $this->redGoatActions = (!empty($info['redGoatActions'])) ? $info['redGoatActions'] : null;
        $this->blueGoatActions = (!empty($info['blueGoatActions'])) ? $info['blueGoatActions'] : null;
    }
}
