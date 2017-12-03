# GoatBattle

v0.1

This is a game. It's very much still under development...

## The Rules

### How to design your own competive \GoatBattle\Goat
1. Your goat class must extend \GoatBattle\Goat
1. Your define three attributes which cannot add up to more than 20
  - Speed : determines how much you can do each turn
  - Horns : determines how damage your ram() inflicts
  - Toughness : determines how much ram()ing you can withstand
1. Your sub-class must implement Goat::action(Situation $situation) which takes a Situation object as a prameter and returns an array of actions.
1. Add your class file to src/GoatBattle and submit a PR
1. When your class is merged it can battle other goats! @TODO URL HERE

### More specs
1. The playing field is 100 x 100 units tall and wide
1. Goats can interact with the environment in one of three ways:
  - turn($n) : $n is an integer between 1-8. 1 is 45 degrees counterclockwise. -2 is 90 degrees clockwise. etc
  - move($n) : $n is how many units forward to move
  - ram() : Will inflict $this->horns of damage if the facing the opponent goat and adjascent cost is determined by the value of `$this->horns - $this->toughness`. Minimum cost is 1

## Developers

### Installation
1. `git clone [GITHUB URL]`
1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar install`.

If Composer is installed globally, run
```bash
composer install
```

You should now be able to run: `bin/cake server`
And visit: http://localhost:8765/battles/view/Quicky/Pokey (substitute the names of the goats)