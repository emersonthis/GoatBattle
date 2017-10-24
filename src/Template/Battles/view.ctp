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
<div>
    <strong>{{battle.goat1.name}}</strong> vs <strong>{{battle.goat2.name}}</strong> Round {{viewRound}}
</div>
<div class="yard">
    <goat class="goat goat--red" :x="redGoat.x" :y="redGoat.y" :direction="redGoat.direction"></goat>
    <goat class="goat goat--blue" :x="blueGoat.x" :y="blueGoat.y" :direction="blueGoat.direction"></goat>
</div><!-- .yard -->
<button id="gobtn" v-on:click="startBattle()">GO!</button>
<?php // debug($battle); ?>
</div><!-- container -->
<script src="https://unpkg.com/vue"></script>
<script>
var battle = <?= json_encode($battle); ?>;
Vue.component('goat', {
  template: '<svg :style="makeStyle()" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="275.163px" height="266.377px" viewBox="4.583 284.427 275.163 266.377" enable-background="new 4.583 284.427 275.163 266.377" xml:space="preserve"><path d="M175.339,294.093c54.119-4.919,54.285,37.384,87.297,67.223c5.417,4.896,31.621,42.283,5.102,32.338 c-22.092-8.285-8.618,9.317-12.531,22.571c-9.431-7.551-12.829-18.679-15.066-30.016c-20,23.704-19.752,58.286-38.361,81.089 c-11.552,14.156-20.795,28.917-23.15,48.776c-1.643,13.858,10.646,19.301,9.148,30.715c-15.383-3.043-24.414-7.082-17.385-29.415 c6.178-19.633,0.963-39.86-0.641-59.685c2.803,24.718-35.9,17.205-50.08,16.082c-9.31-0.737-32.291-0.143-39.345-8.074 c-1.977-2.223,0.952-22.174-0.085-26.184c-3.579,26.616-37.166,63.308-27.356,89.379c2.591,6.889,21.632,21.727,1.546,21.911 c-8.683,0.079-12.572-16.331-12.36-22.382c0.537-15.334,14.633-46.146,7.145-60.268c-12.238-23.08-30.956-50.141-11.301-76.511 c-10.89,4.745-17.143,14.67-25.871,22.084c-19.13-34.254,12.66-25.571,33.251-28.484c26.991-3.818,39.99-16.749,68.236-2.929 c16.969,8.319,56.231,9.152,73.5,1.272c16.434-7.499,18.623-27.693,39.008-31.931c-10.904-0.502-21.809-1.002-32.714-1.5 c10.945-11.587,24.224-14.84,39.035-8.821C226.717,311.296,202.895,299.479,175.339,294.093 C199.476,291.899,202.256,299.354,175.339,294.093z"/> </svg>',
  props: ['x','y','direction'],
  methods : {
    makeStyle : function() {
        var x = (this.x + 50);
        var y = (this.y + 50);
        var flip = (this.direction > 90 == this.direction < 270) ? ' scaleY(-1)' : '';        
        return "left:"+x+"%;bottom:"+y+"%;transform:rotate(-"+this.direction+"deg)"+flip+";";
    }
  }
});

var app = new Vue({
  el: '#app',
  data: {
    battle: battle,
    viewRound: 0,
    roundDuration: 250,
    actionDuration: 250,
    redGoat: {
        x: -50,
        y: 50,
        direction: 315,
        name: battle.goat1.name
    },
    blueGoat: {
        x: 50,
        y: -50,
        direction: 135,
        name: battle.goat2.name
    }
  },
  methods : {
    startBattle : function() {
        console.log('Start!');
        for (var i=0; i<this.battle.battleTranscript.length; i++) {

            var redGoatActions = this.battle.battleTranscript[i].redGoatActions;
            var blueGoatActions = this.battle.battleTranscript[i].blueGoatActions;

            var doAction = function(action, that, redOrBlue) {
                if (redOrBlue == 'RED') {
                    app.redGoat.x = action.endLocation.x;
                    app.redGoat.y = action.endLocation.y;
                    app.redGoat.direction = action.endLocation.direction;
                } else {
                    app.blueGoat.x = action.endLocation.x;
                    app.blueGoat.y = action.endLocation.y;
                    app.blueGoat.direction = action.endLocation.direction;
                }
            };

            //@TODO DRY this up
            for (var j = 0; j < redGoatActions.length; j++) {
                var delay = this.roundDuration*i + this.actionDuration*j;
                setTimeout(function(index, action, goat, that, i) { return function() {
                    app.viewRound = i;
                    doAction(action, that, 'RED');
                }; }(j, redGoatActions[j], this.redGoat, this, i), delay);
            }
            
            for (var k = 0; k < blueGoatActions.length; k++) {
                var delay = this.roundDuration*i + this.actionDuration*k;
                setTimeout(function(index, action, goat, that, i) { return function() {
                    app.viewRound = i;
                    doAction(action, that, 'BLUE');
                }; }(k, blueGoatActions[k], this.blueGoat, this, i), delay);
            }

        }
        console.log('The End');
    }
  },
  computed : {
  }
});


</script>
</body>
</html>
