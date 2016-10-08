<?php
namespace App\GoatBattle;

use ReflectionClass;

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
            
            $goat1Actions = $this->turn($this->goat1, $this->goat1Location, $this->goat2, $this->goat2Location);
            $goat2Actions = $this->turn($this->goat2, $this->goat2Location, $this->goat1, $this->goat1Location);

            $roundActions = ['goat1' => $goat1Actions, 'goat2' => $goat2Actions];
            
            $this->battleTranscript[] = ['round' => $this->roundCount, 'actions' => $roundActions];
        }
        $this->determineOutcome();
    }

    /**
     *
     */
    public function turn(
        Goat $thisGoat,
        GoatLocation $thisGoatLocation,
        Goat $otherGoat,
        GoatLocation $otherGoatLocation
    ) {
        $goatActions = $this->getGoatActions($thisGoat, $otherGoatLocation);
        $realGoatActions = $this->authorizeActions($thisGoat, $goatActions, $thisGoatLocation, $otherGoatLocation);
        $this->updateGoat($thisGoat, $goatActions);
        return $realGoatActions;
    }

    /**
     *
     */
    public function authorizeActions(Goat $goat, $goatActions, $goatLocation, $otherGoatLocation)
    {
        $actions = [];
        $availableAction = $goat->speed();

        foreach ($goatActions as $action) {
            if (!$action instanceof Action) {
                $this->log("Invalid action:");
                $this->log($action);
                return $actions;
            }

            if ($action->cost() <= $availableAction) {
                $actions[] = $action;
                $availableAction -= $action->cost();
            } else {
                if ($action->isMove()) {
                    $action->measure = $availableAction;
                    $actions[] = $action;
                }
                if ($action->isTurn()) {
                    $action->measure = ($action->measure > 0) ? $availableAction : $availableAction * -1;
                    $actions[] = $action;
                }
                // ramming but no action left
                $availableAction = 0;
            }

        }

        return $actions;
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
    public function getGoatActions(Goat $goat, GoatLocation $opponentGoatLocation)
    {
        $opponentGoatLocation = clone $opponentGoatLocation;
        $roundActionsFromGoat = $goat->action($opponentGoatLocation);
        return $roundActionsFromGoat;
    }

    /**
     *
     */
    private function updateGoat($goat, $roundActions)
    {
        // $availableAction = $goat->speed();
        $actions = [];

        // $i = 0;
        // $c = count($roundActions);
        // while ($availableAction > 0 && $i < $c) {
        foreach ($roundActions as $action) {
            // $action = $roundActions[$i];
            // $i++;

            // // $reflect = new ReflectionClass($action); //@TODO
            // if (!$action instanceof Action) {
            //     throw new Exception('Invalid action!');
            //     debug($action);
            //     break;
            // }

            if ($action->cost() <= $availableAction) {
                $action->endLocation = $this->applyAction($goat, $action);
                $availableAction -= $action->cost();
                $actions[] = $action;
            } else {
                if ($action->isAdvance()) {
                    $action->measure = $availableAction;
                    $action->endLocation = $this->applyAction($goat, $action);
                    $actions[] = $action;
                }
                if ($action->isTurn()) {
                    $action->measure = ($action->measure > 0) ? $availableAction : $availableAction * -1;
                    $action->endLocation = $this->applyAction($goat, $action);
                    $actions[] = $action;
                }
                // ramming but no action left
                $availableAction = 0;
            }
        }
        return $actions;
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
        echo "The battle begins!\n\n";

        echo '=Round: 0=' . "\n";
        echo 'GOAT1: Starts ' . $this->goat1StartLocation->describe() . "\n";
        echo 'GOAT2: Starts ' . $this->goat2StartLocation->describe() . "\n";
        echo "\n";

        foreach ($this->battleTranscript as $line) {
            echo '=Round: ' . $line['round'] . "=\n";
            echo 'GOAT1: ' . $this->describeActionRound($line['actions']['goat1']) . "\n";
            echo 'GOAT2: ' . $this->describeActionRound($line['actions']['goat2']) . "\n";
            echo "\n\n";
        }

        echo $this->outcomesMap[$this->outcome] . "\n";

        echo "The End.\n\n";
    }

    /**
     *
     */
    private function describeActionRound($actions)
    {
        $statement = '';
        foreach ($actions as $action) {
            $statement .= $action->describe() . ' ';
        }

        $statement .= $actions[count($actions) - 1]->endLocation->describe();

        return $statement;
    }
}
