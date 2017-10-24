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
<?php debug($battle); ?>
</div><!-- container -->
<script src="https://unpkg.com/vue"></script>
<script>
var battle = <?= json_encode($battle); ?>;
Vue.component('goat', {
  template: '<svg :style="makeStyle()" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="275.163px" height="266.377px" viewBox="4.583 284.427 275.163 266.377" enable-background="new 4.583 284.427 275.163 266.377" xml:space="preserve"><path d="M110.221,294.093c-26.917,5.261-50.796,14.105-57.021,47.242c14.717-5.981,26.943-3.98,39.035,8.821 c-11.233,0.513-20.284,0.927-32.714,1.5c17.546,3.648,23.958,16.177,32.195,26.231c4.439,5.425,8.634,7.506,15.278,6.806 c9.212-0.966,18.065,0.275,27.306,2.455c12.577,2.979,26.118,0.859,37.729-4.833c19.814-9.694,39.059-11.245,58.64-0.641 c6.587,3.571,12.331,5.314,20.023,1.902c8.116-3.591,16.807-0.028,24.302,3.668c6.924,3.404,6.156,12.815-1.478,26.484 c-8.523-7.24-14.53-17.144-25.871-22.084c6.735,9.037,8.027,19.017,7.466,29.718c-0.699,13.532-2.979,25.843-11.977,37.02 c-7.457,9.264-14.912,20.032-4.379,32.529c2.141,2.537-0.453,5.386-0.572,8.119c-0.295,6.42-2.741,13.957,1.185,18.957 c8.581,10.922,1.715,20.23-1.993,29.327c-2.18,5.357-9.832,3.345-13.938,1.814c-5.465-2.041-0.72-6.639,1.125-8.425 c16.107-15.573,9.262-32.006,0.956-47.312c-8.878-16.364-17.339-32.601-20.2-53.877c-1.834,7.092-2.1,11.786-0.206,16.007 c4.468,9.955-1.055,12.478-9.272,14.113c-19.896,3.949-39.916,5.477-60.172,4.825c-10.984-0.347-21.733-0.247-19.859-16.77 c-1.644,20.329-6.891,39.82-0.641,59.685c2.727,8.663-3.373,17.224-1.993,26.861c0.708,4.972-9.947,1.477-15.392,2.554 c-0.181-1.381-0.674-2.518-0.334-2.892c21.523-23.89,1.412-45.631-3.458-68.146c-21.364-11.789-18.519-36.379-28.479-54.223 c-6.208-11.114-10.139-23.524-20.091-35.319c-2.441,12.37-5.593,22.431-15.066,30.016c-2.126-7.2,1.162-13.099,1.429-19.165 c0.266-5.967-1.154-7.959-7.645-5.918c-5.913,1.865-13.583,7.801-18.051-1.352c-3.892-7.971,0.286-15.457,6.729-20.219 c17.841-13.207,30.611-28.975,39.741-50.127C62.568,296.225,86.084,291.899,110.221,294.093z"/></svg>',
  props: ['x','y','direction'],
  methods : {
    makeStyle : function() {
        var x = (this.x + 50);
        var y = (this.y + 50);
        // console.log('x',x);
        // console.log('y',y);
        console.log('makeStyle', this);
        return "left:"+x+"%;bottom:"+y+"%;transform:rotate("+this.direction+");";
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

            // for (var j=0; j<redGoatActions.length; j++) {
            //     var that1 = this;
            // }

            var doAction = function(action, that) {
                // console.log('this in doAction', this);
                // console.log('that in doAction', that);
                // console.log('app in doAction', app);

                app.redGoat.x = action.endLocation.x;
                app.redGoat.y = action.endLocation.y;
                app.redGoat.direction = action.endLocation.direction;
                // console.log(action);
            };

            for (var j = 0; j < redGoatActions.length; j++) {
                
                // console.log('j', j);

                var delay = this.roundDuration*i + this.actionDuration*j;

                setTimeout(function(index, action, goat, that, i) { return function() {

                    // console.log('index', index);
                    app.viewRound = i;

                    console.log(goat.name + 'action', action.actionsMap[action.type], action.measure);
                    console.log('goat', goat);
                    // console.log('that', that);
                    doAction(action, that);
                    console.log('app.redGoat', app.redGoat);
                    console.log('action',action);

                }; }(j, redGoatActions[j], this.redGoat, this, i), delay);
            }
            

            for (var k = 0; k < blueGoatActions.length; k++) {
                
                var delay = this.roundDuration*i + this.actionDuration*k;

                setTimeout(function(index, action, goat, that, i) { return function() {

                    // console.log('index', index);
                    app.viewRound = i;

                    // console.log(goat.name + 'action', action.actionsMap[action.type], action.measure);
                    // console.log('goat', goat);
                    // console.log('that', that);
                    doAction(action, that);
                    // console.log('app.redGoat', app.redGoat);
                    // console.log('action',action);

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
