<?php
namespace App\GoatBattle;

class Battle
{
    public $goat1;
    public $goat2;
    public $winner = false;
    public $battleTranscript = [];
    public $roundCount = 0;
    private $maxRounds = 500;
    private $outcomesMap = [
        1 => 'Goat 1 wins!',
        2 => 'Goat 2 wins!',
        3 => 'Neither goat wins. Tie!',
        4 => 'Both goats loose. Tie!'
    ];
    public $outcome;

    /**
     *
     */
    public function __construct(Goat $goat1, Goat $goat2)
    {
        $this->goat1 = $goat1;
        $this->goat2 = $goat2;

        if (!$this->goat1->validateAttributes()) {
            $this->battleTranscript[] = "Goat1 invalid atts";
        }
        if (!$this->goat2->validateAttributes()) {
            $this->battleTranscript[] = "Goat2 invalid atts";
        }

        while ($this->gameOn()) {
            $this->roundCount++;
            $roundActions[] = $this->getActions();
        }

        $this->determineOutcome();

        debug($this->battleTranscript);
        echo $this->outcomesMap[$this->outcome];
    }

    /**
     *
     */
    private function determineOutcome()
    {
        if ($this->goat1->ok() && !$this->goat2->ok()) {
            $this->winner = $this->goat1;
            $this->outcome = 1;
        }

        if ($this->goat2->ok() && !$this->goat1->ok()) {
            $this->winner = $this->goat2;
            $this->outcome = 2;
        }

        if ($this->roundCount == $this->maxRounds) {
            $this->outcome = 3;
        }

        if (!$this->goat2->ok() && !$this->goat1->ok()) {
            $this->outcome = 4;
        }
    }

    /**
     *
     */
    private function getActions()
    {
        $roundActionFromGoat1 = $this->goat1->action(clone $this->goat2->location);
        $roundActionFromGoat2 = $this->goat2->action(clone $this->goat1->location);

        $realRoundActionFromGoat1 = $this->updateGoat($this->goat1, $roundActionFromGoat1);
        $realRoundActionFromGoat2 = $this->updateGoat($this->goat2, $roundActionFromGoat2);

        return ['goat1_action' => $realRoundActionFromGoat1, 'goat2_action' => $realRoundActionFromGoat2];
    }

    /**
     *
     */
    private function updateGoat($goat, $roundAction)
    {
        // get max movement for this goat

        // calculate "cost" of actions

        //
    }

    /**
     *
     */
    public function gameOn()
    {
        if ($this->roundCount >= 500) {
            return false;
        }
        return ($this->goat1->ok() && $this->goat2->ok());
    }

    /**
     *
     */
    public function printTranscript()
    {
        echo "The battle begins!\n";

        foreach ($this->battleTranscript as $line) {
            echo $line . "\n";
        }
    }
}
