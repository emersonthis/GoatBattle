<?php
namespace App\GoatBattle;

class Battle
{
    public $goat1;
    public $goat2;
    public $winner = false;
    public $battleTranscript = [];
    public $roundCount = 0;
    private $maxRounds = 50;
    private $outcomesMap = [
        1 => 'Goat 1 wins!',
        2 => 'Goat 2 wins!',
        3 => 'Neither goat wins. Tie!',
        4 => 'Both goats loose. Tie!'
    ];
    public $outcome;
    private $goat1Location;
    private $goat2Location;

    /**
     *
     */
    public function __construct(Goat $goat1, Goat $goat2)
    {
        $this->goat1Location = new GoatLocation('RED');
        $this->goat2Location = new GoatLocation('BLUE');

        $this->goat1 = $goat1;
        $this->goat2 = $goat2;

        $this->goat1->setLocation($this->goat1Location);
        $this->goat2->setLocation($this->goat2Location);

        if (!$this->goat1->validateAttributes()) {
            $this->battleTranscript[] = "Goat1 invalid atts";
        }
        if (!$this->goat2->validateAttributes()) {
            $this->battleTranscript[] = "Goat2 invalid atts";
        }

        while ($this->gameOn()) {
            $this->roundCount++;
            $roundActions = $this->getActions();
            $this->battleTranscript[] = ['round' => $this->roundCount, 'actions' => $roundActions];
        }

        $this->determineOutcome();

        // debug($this->battleTranscript);
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
        $roundActionsFromGoat1 = $this->goat1->action(clone $this->goat2->location);
        $roundActionsFromGoat2 = $this->goat2->action(clone $this->goat1->location);

        // debug($roundActionsFromGoat1);exit;
        $realRoundActionsGoat1 = $this->updateGoat($this->goat1, $roundActionsFromGoat1);
        $realRoundActionsGoat2 = $this->updateGoat($this->goat2, $roundActionsFromGoat2);

        return ['goat1' => $realRoundActionsGoat1, 'goat2' => $realRoundActionsGoat2];
    }

    /**
     *
     */
    private function updateGoat($goat, $roundActions)
    {
        $availableAction = $goat->speed();
        $actions = [];

        $i = 0;
        $c = count($roundActions);
        while ($availableAction > 0 && $i < $c) {
            $action = $roundActions[$i];
            $i++;
            // debug($roundActions);
            // debug($action);
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

    private function applyAction(Goat $actionGoat, Action $action, $actionMeasureOverride = null)
    {
        $measure = ($actionMeasureOverride) ? $actionMeasureOverride : $action->measure;
        if ($action->isRam()) {
            //@TODO
        }

        if ($action->isTurn()) {
            $actionGoat->turn($measure);
        }

        if ($action->isAdvance()) {
            $actionGoat->turn($measure);
        }

        return $actionGoat->location;
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

        foreach ($this->battleTranscript as $line) {
            // debug($line);exit;
            echo '=Round: ' . $line['round'] . "=\n";
            // echo "===========\n";
            echo 'GOAT1: ' . $this->describeActionRound($line['actions']['goat1']) . "\n";
            // echo 'GOAT2: ' . $this->describeActionRound($line['actions']['goat2']) . "\n";
            echo 'GOAT2: coming soon';
            echo "\n\n";
        }

        echo $this->outcomesMap[$this->outcome] . "\n";

        echo "The End.\n\n";
    }

    private function describeActionRound($actions)
    {
        $statement = '';
        foreach ($actions as $action) {
            $statement .= $action->describe() . ' ';
        }
        // debug( $actions );
        // debug( $actions[count($actions) - 1] );
        // debug( $actions[count($actions) - 1]->endLocation );
        $statement .= $actions[count($actions) - 1]->endLocation->describe();
        // debug( $statement );
        // exit;
        return $statement;
    }
}