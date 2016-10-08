<?php
namespace App\Shell;

use App\GoatBattle\Battle;
use App\GoatBattle\Fatty;
use App\GoatBattle\Goat;
use App\GoatBattle\GoatLocation;
use App\GoatBattle\Quicky;
use App\GoatBattle\Stilly;
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
        $stilly = new Stilly();
        
        $battle = new Battle($quicky, $stilly);
        $battle->go();

        echo "\n";
        $battle->printTranscript();
    }
}
