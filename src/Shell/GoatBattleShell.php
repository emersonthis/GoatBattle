<?php
namespace App\Shell;

use GoatBattle\Battle;
use GoatBattle\Fatty;
use GoatBattle\Goat;
use GoatBattle\Location;
use GoatBattle\Quicky;
use GoatBattle\Pokey;
use Cake\Console\Shell;

/**
 * GoatBattle shell command.
 */
class GoatBattleShell extends Shell
{

    public $battleTranscript = [];

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        // $this->out($this->OptionParser->help());

        $quicky = new Quicky();
        // $fatty = new Fatty();
        $stilly = new Pokey();
        
        $battle = new Battle($quicky, $stilly);
        $battle->go();

        echo "\n";
        $battle->printTranscript();
        // debug($battle->battleTranscript);
    }
}
