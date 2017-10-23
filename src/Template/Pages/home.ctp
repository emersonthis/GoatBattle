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

<div class="yard">
    <?php echo $this->Html->image('simplegoat--red.svg', ['class'=>'goat goat--red']); ?>
    <?php echo $this->Html->image('simplegoat--blue.svg', ['class'=>'goat goat--blue']); ?>
</div><!-- .yard -->

</body>
</html>
