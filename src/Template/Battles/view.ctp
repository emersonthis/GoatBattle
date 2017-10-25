<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;
?><!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <?= $this->Html->css('goats'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOATS!</title>
</head>
<body>
<div class="container" id="app">
<div class="header">
    <div class="header__goat header__goat--red">
        <strong class="header__goatname">{{battle.goat1.name}}</strong>
        <div>({{redGoat.x}},{{redGoat.y}}) {{redGoat.direction}}&deg; &hearts;=<span>{{redGoat.health}}</span></div>
    </div>
    <div class="header__middle">
        <div>Round {{viewRound}}</div>
        <div>{{displayOutcome()}}</div>
    </div>
    <div class="header__goat header__goat--blue">
        <strong class="header__goatname">{{battle.goat2.name}}</strong>
        <div>({{blueGoat.x}},{{blueGoat.y}}) {{blueGoat.direction}}&deg; &hearts;=<span>{{blueGoat.health}}</span></div>
    </div>
</div>
<div class="yard">
    <goat class="goat goat--red" :x="redGoat.x" :y="redGoat.y" :direction="redGoat.direction"></goat>
    <goat class="goat goat--blue" :x="blueGoat.x" :y="blueGoat.y" :direction="blueGoat.direction"></goat>
</div><!-- .yard -->
<button id="gobtn" v-on:click="startBattle()">GO!</button>
<?php debug($battle); ?>
</div><!-- container -->
<script src="https://unpkg.com/vue"></script>
<script>var battle = <?= json_encode($battle); ?>;</script>
<?= $this->Html->script('battle'); ?>
</body>
</html>
