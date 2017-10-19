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
    public $maxRounds = 50;
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

        // $this->goat1->setLocation($this->goat1Location);
        // $this->goat2->setLocation($this->goat2Location);

        if (!$this->goat1->validateAttributes()) {
            throw new Exception("Goat1 invalid atts");
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
            
            $goat1Actions = $this->takeTurn($this->goat1, $this->goat1Location, $this->goat2, $this->goat2Location);
            $goat2Actions = $this->takeTurn($this->goat2, $this->goat2Location, $this->goat1, $this->goat1Location);

            $roundActions = [
                'goat1' => $goat1Actions,
                'goat2' => $goat2Actions
            ];
            
            $this->battleTranscript[] = [
                'round' => $this->roundCount,
                'actions' => $roundActions,
                'goat1EndingLocation' => clone $this->goat1Location,
                'goat2EndingLocation' => clone $this->goat2Location,
            ];
        }
        $this->determineOutcome();
    }

    /**
     *
     */
    public function takeTurn(
        Goat $thisGoat,
        GoatLocation $thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation
    ) {
        $goatActions = $this->getGoatActions($thisGoat, $thisGoatLocation, $otherGoatLocation);
        // debug($goatActions);
        $realGoatActions = $this->authorizeActions($thisGoat, $goatActions, $thisGoatLocation, $otherGoatLocation);
        // debug($realGoatActions);
        foreach ($realGoatActions as $realAction) {
            $newLocation = $this->updateGoat($thisGoat, $thisGoatLocation, $otherGoat, $otherGoatLocation, $realAction);
            // $thisGoatLocation->x = $newLocation->x;
            // $thisGoatLocation->y = $newLocation->y;
            // $thisGoatLocation->direction = $newLocation->direction;
            // debug($newLocation);exit;
        }
        return $realGoatActions;
    }

    /**
     * //@TODO LIMIT NUMBER OF ACTIONS TO THE MAX POSSIBLE SPEED
     */
    public function authorizeActions(Goat $goat, $goatActions, GoatLocation $goatLocation, GoatLocation $otherGoatLocation)
    {
        $actions = [];
        $availableAction = $goat->speed;
        // debug($availableAction);

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
        GoatLocation $thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation,
        Action $action
    ) {
        $newLocation = $action->apply($thisGoat, $thisGoatLocation, $otherGoat, $otherGoatLocation);
        // $thisGoatLocation = $newLocation;
        return $newLocation;
    }

    // /**
    //  *
    //  */
    // private function applyAction(Goat $actionGoat, Action $action, $actionMeasureOverride = null)
    // {
    //     $measure = ($actionMeasureOverride) ? $actionMeasureOverride : $action->measure;
    //     if ($action->isRam()) {
    //         $actionGoat->ram();
    //     }

    //     if ($action->isTurn()) {
    //         $actionGoat->turn($measure);
    //     }

    //     if ($action->isAdvance()) {
    //         $actionGoat->move($measure);
    //     }
    //     return $actionGoat->location;
    // }

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
        // debug($this->battleTranscript);exit;
        echo "The battle begins!\n\n";

        // echo '=Round: 0=' . "\n";
        // echo 'GOAT1: Starts ' . $this->goat1StartLocation->describe() . "\n";
        // echo 'GOAT2: Starts ' . $this->goat2StartLocation->describe() . "\n";
        // echo "\n";

        // foreach ($this->battleTranscript as $line) {
        //     echo '=Round: ' . $line['round'] . "=\n";
        //     echo 'GOAT1: ' . $this->describeActionRound($line['actions']['goat1']) . "\n";
        //     echo 'GOAT2: ' . $this->describeActionRound($line['actions']['goat2']) . "\n";
        //     echo "\n\n";
        // }

        // echo $this->outcomesMap[$this->outcome] . "\n";

        // debug($this->goat1StartLocation);
        // debug($this->goat2StartLocation);
        // debug($this->battleTranscript);

        // debug($this->goat2);

        foreach ($this->battleTranscript as $round) {
            echo "In round {$round['round']}...\n";

            echo "{$this->goat1->name()} ";
            foreach ($round['actions']['goat1'] as $action) {
                echo $action->describe() . ", ";
            }
            echo "...ending at {$round['goat1EndingLocation']->x},{$round['goat1EndingLocation']->y} facing {$round[
                'goat1EndingLocation']->facing()}\n";

            echo "{$this->goat2->name()} ";
            foreach ($round['actions']['goat2'] as $action) {
                echo $action->describe() . ", ";
            }
            echo "...ending at {$round['goat2EndingLocation']->x},{$round['goat2EndingLocation']->y} facing {$round[
                'goat2EndingLocation']->facing()}\n\n";

        }

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
