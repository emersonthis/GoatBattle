<?php
namespace GoatBattle;

use Cake\Log\Log;

class Battle
{
    public $goat1;
    public $goat2;
    public $winner = false;
    public $battleTranscript = [];
    public $roundCount = 0;
    public $maxRounds = 100;
    public $outcomesMap = [
        # we personalize 1 and 2 in __construct
        1 => 'Goat 1 wins!',
        2 => 'Goat 2 wins!',
        3 => 'Neither goat wins. Tie!',
        4 => 'Both goats loose. Tie!'
    ];
    public $outcome = false;
    public $goat1Location; # do we even need to store this property?
    public $goat2Location;

    public $goat1StartLocation; # do we even need to store this property?
    public $goat2StartLocation;

    public $currentSituation;

    /**
     *
     */
    public function __construct(Goat $goat1, Goat $goat2)
    {
        $this->goat1 = $goat1;
        $this->goat2 = $goat2;

        # customize the outcome text with goat names
        $this->outcomesMap[1] = "{$goat1->name} wins!";
        $this->outcomesMap[2] = "{$goat2->name} wins!";

        $this->goat1Location = new Location('RED');
        $this->goat2Location = new Location('BLUE');

        $this->goat1StartLocation = clone $this->goat1Location;
        $this->goat1->color = 'RED';
        
        $this->goat2StartLocation = clone $this->goat2Location;
        $this->goat2->color = 'BLUE';

        if (!$this->goat1->validateAttributes()) {
            throw new \Exception("Goat1 invalid atts");
        }
        if (!$this->goat2->validateAttributes()) {
            throw new \Exception("Goat2 invalid atts");
        }

        $this->currentSituation = new Situation([
            'redGoat' => $this->goat1,
            'blueGoat' => $this->goat2,
            'redLocation' => new Location('RED'),
            'blueLocation' => new Location('BLUE')
        ]);
    }

    /**
     *
     */
    public function go()
    {
        while ($this->gameOn()) {
            $this->roundCount++;

            $newRound = new Round(
                [
                    'number' => $this->roundCount,
                ]
            );
            
            $goat1Actions = $this->takeTurn($this->goat1, $this->currentSituation);

            $newRound->redGoatActions = $goat1Actions;

            # Handle red winning mid-round
            if ($this->goat2->health < 0) {
                $newRound->blueGoatActions = [];
                $this->battleTranscript[] = $newRound;
                break;
            }
            
            $goat2Actions = $this->takeTurn($this->goat2, $this->currentSituation);

            $newRound->blueGoatActions = $goat2Actions;
            
            $this->battleTranscript[] = $newRound;
        }
        $this->determineOutcome();
    }

    /**
     *
     */
    public function takeTurn(Goat $thisGoat, Situation $situation)
    {
        $goatActions = $this->getGoatActions($thisGoat, $situation);
        $realGoatActions = $this->authorizeActions($thisGoat, $goatActions);
        foreach ($realGoatActions as $realAction) {
            $this->currentSituation = $this->updateGoat($thisGoat, $realAction, $situation);
        }
        return $realGoatActions;
    }

    /**
     *
     */
    public function updateGoat(
        Goat $thisGoat,
        Action $action,
        Situation $situation
    ) {
        $newSituation = $action->apply($thisGoat, $situation);
        return $newSituation;
    }

    /**
     *
     */
    public function authorizeActions(Goat $goat, $goatActions)
    {
        $actions = [];
        $availableAction = $goat->speed;

        foreach ($goatActions as $action) {
            if (!$action instanceof Action) {
                Log::write('debug', "Invalid action:");
                Log::write('debug', $action);
                return $actions;
            }

            # actinos that are under budget we pass through
            if ($action->cost($goat) <= $availableAction) {
                $actions[] = $action;
                $availableAction -= $action->cost($goat);
            # actions that are over budget we try to trim
            } else {
                if ($action->isMove()) {
                    $action->measure = $availableAction;
                    $actions[] = $action;
                }
                if ($action->isTurn()) {
                    $actions[] = $this->trimTurn($action, $availableAction);
                }
                // ramming but no action left
                $availableAction = 0;
                break;
            }

        }

        return $actions;
    }

    /**
     * Trim Turn
     * @param Action $action the action to trim
     * @param int $trimTo the value to trim to
     * @return Action
     */
    public function trimTurn($action, $trimTo)
    {
        if (!$action->isTurn()) {
            throw new Exception('Non-turn action passed');
        }
        if ($trimTo < 0) {
            throw new Exception('Second param cannot be negative');
        }

        //these should use abs()
        if ($action->measure > 0) {
            $action->measure = $trimTo;
        }
        if ($action->measure < 0) {
            $action->measure = $trimTo * -1;
        }
        return $action;
    }

    /**
     *
     */
    private function determineOutcome()
    {
        if ($this->currentSituation->redGoat->ok() && !$this->currentSituation->blueGoat->ok()) {
            $this->winner = $this->goat1;
            $this->outcome = 1;
        }

        if (!$this->currentSituation->redGoat->ok() && $this->currentSituation->blueGoat->ok()) {
            $this->winner = $this->goat2;
            $this->outcome = 2;
        }

        if ($this->roundCount == $this->maxRounds) {
            $this->outcome = 3;
        }

        if (!$this->currentSituation->redGoat->ok() && !$this->currentSituation->blueGoat->ok()) {
            $this->outcome = 4;
        }
    }

    /**
     *
     */
    public function outcomeText()
    {
        return $this->outcomesMap[$this->outcome];
    }

    /**
     *
     */
    public function getGoatActions(Goat $goat, Situation $situation)
    {
        // $opponentLocation = clone $opponentLocation;
        $roundActionsFromGoat = $goat->action($situation);
        return $roundActionsFromGoat;
    }

    /**
     *
     */
    public function gameOn()
    {
        if ($this->roundCount >= $this->maxRounds) {
            return false;
        }
        return ($this->currentSituation->redGoat->ok() && $this->currentSituation->blueGoat->ok());
    }

    /**
     *
     */
    public function printTranscript()
    {
        echo "The battle begins!\n\n";

        foreach ($this->battleTranscript as $round) {
            echo "In round {$round->number}...\n";

            echo "{$round->redGoat->name()} ";
            foreach ($round->redGoatActions as $action) {
                echo $action->describe() . ", ";
            }
            echo "...ending at {$round->redGoatEndLocation->x},{$round->blueGoatEndLocation->y} facing {$round->blueGoatEndLocation->facing()}\n";

            echo "{$round->blueGoat->name()} ";
            foreach ($round->blueGoatActions as $action) {
                echo $action->describe() . ", ";
            }
            echo "...ending at {$round->blueGoatEndLocation->x},{$round->blueGoatEndLocation->y} facing {$round->blueGoatEndLocation->facing()}\n\n";
        }

        echo $this->outcomeText() . "\n\n";

        echo "The End.\n\n";
    }

    /**
     * @TODO does this even get used? It should live in Action
     */
    private function describeActionRound($actions)
    {
        if (empty($actions)) {
            return 'Nothing';
        }
        $statement = '';
        foreach ($actions as $action) {
            $statement .= $action->describe() . ' ';
        }
        $statement .= end($actions)->endLocation->describe();

        return $statement;
    }
}
