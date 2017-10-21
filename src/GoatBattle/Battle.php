<?php
namespace App\GoatBattle;

use Cake\Log\Log;

class Battle
{
    public $goat1;
    public $goat2;
    public $winner = false;
    public $battleTranscript = [];
    public $roundCount = 0;
    public $maxRounds = 100;
    private $outcomesMap = [
        1 => 'Goat 1 wins!',
        2 => 'Goat 2 wins!',
        3 => 'Neither goat wins. Tie!',
        4 => 'Both goats loose. Tie!'
    ];
    public $outcome = false;
    public $goat1Location;
    public $goat2Location;

    public $goat1StartLocation;
    public $goat2StartLocation;

    /**
     *
     */
    public function __construct(Goat $goat1, Goat $goat2)
    {
        $this->goat1 = $goat1;
        $this->goat2 = $goat2;

        $this->goat1Location = new GoatLocation('RED');
        $this->goat2Location = new GoatLocation('BLUE');

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
                    'redGoat' => $this->goat1,
                    'blueGoat' => $this->goat2,
                    'redGoatStartLocation' => clone $this->goat1Location,
                    'blueGoatStartLocation' => clone $this->goat2Location
                ]
            );
            
            $goat1Actions = $this->takeTurn(
                $this->goat1,
                $this->goat1Location,
                $this->goat2,
                $this->goat2Location
            );

            $newRound->redGoatActions = $goat1Actions;
            $newRound->redGoatEndLocation = clone $this->goat1Location;
            
            $goat2Actions = $this->takeTurn(
                $this->goat2,
                $this->goat2Location,
                $this->goat1,
                $this->goat1Location
            );

            $newRound->blueGoatActions = $goat2Actions;
            $newRound->blueGoatEndLocation = clone $this->goat2Location;
            
            $this->battleTranscript[] = $newRound;
        }
        $this->determineOutcome();
    }

    /**
     *
     */
    public function takeTurn(
        Goat $thisGoat,
        GoatLocation &$thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation
    ) {
        $goatActions = $this->getGoatActions($thisGoat, $thisGoatLocation, $otherGoatLocation);
        $realGoatActions = $this->authorizeActions($thisGoat, $goatActions, $thisGoatLocation, $otherGoatLocation);
        foreach ($realGoatActions as $realAction) {
            $thisGoatLocation = $this->updateGoat($thisGoat, $thisGoatLocation, $otherGoat, $otherGoatLocation, $realAction);
        }
        return $realGoatActions;
    }

    /**
     *
     */
    public function authorizeActions(Goat $goat, $goatActions, GoatLocation $goatLocation, GoatLocation $otherGoatLocation)
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
            if ($action->cost() <= $availableAction) {
                $actions[] = $action;
                $availableAction -= $action->cost();
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
    public function outcomeText()
    {
        return $this->outcomesMap[$this->outcome];
    }

    /**
     *
     */
    public function getGoatActions(Goat $goat, GoatLocation $thisGoatLocation, GoatLocation $opponentGoatLocation)
    {
        $opponentGoatLocation = clone $opponentGoatLocation;
        $roundActionsFromGoat = $goat->action($thisGoatLocation, $opponentGoatLocation);
        return $roundActionsFromGoat;
    }

    /**
     *
     */
    public function updateGoat(
        Goat $thisGoat,
        GoatLocation &$thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation,
        Action $action
    ) {
        $newLocation = $action->apply($thisGoat, $thisGoatLocation, $otherGoat, $otherGoatLocation);
        return $newLocation;
    }

    /**
     *
     */
    public function gameOn()
    {
        if ($this->roundCount >= $this->maxRounds) {
            return false;
        }
        return ($this->goat1->ok() && $this->goat2->ok());
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
     *
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
