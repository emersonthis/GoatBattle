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
    actionDuration: 200,
    redGoat: {
        x: -50,
        y: 50,
        direction: 315,
        health: battle.goat1.toughness,
        name: battle.goat1.name
    },
    blueGoat: {
        x: 50,
        y: -50,
        direction: 135,
        health: battle.goat2.toughness,
        name: battle.goat2.name
    }
  },
  methods : {
    // goatHealth : function(redOrBlue) {
    //     if (this.viewRound) {
    //         return (redOrBlue == 'RED') ? battle.battleTranscript[this.viewRound - 1].redGoat.health : battle.battleTranscript[this.viewRound - 1].blueGoat.health;
    //     }
    //     return (redOrBlue == 'RED') ? battle.battleTranscript[0].redGoat.health : battle.battleTranscript[0].blueGoat.health;
    // },
    startBattle : function() {
        console.log('Start!');

        var timeTracker = 0; //ms of battle duration

        for (var i=0; i<this.battle.battleTranscript.length; i++) {

            var redGoatActions = this.battle.battleTranscript[i].redGoatActions;
            var blueGoatActions = this.battle.battleTranscript[i].blueGoatActions;

            //@TODO We also need to update the goats' health so that we can see the effects of a ram
            var doAction = function(action, that, redOrBlue) {
                if (redOrBlue == 'RED') {
                    app.redGoat.x = action.endSituation.redGoatLocation.x;
                    app.redGoat.y = action.endSituation.redGoatLocation.y;
                    app.redGoat.direction = action.endSituation.redGoatLocation.direction;
                    app.blueGoat.health = action.endSituation.blueGoat.health;
                } else {
                    app.blueGoat.x = action.endSituation.blueGoatLocation.x;
                    app.blueGoat.y = action.endSituation.blueGoatLocation.y;
                    app.blueGoat.direction = action.endSituation.blueGoatLocation.direction;
                    app.redGoat.health = action.endSituation.redGoat.health;
                }
            };

            var isNothingAction = function(action) {
                //0 turn
                if (action.type == 2 && action.measure == 0) {
                    return true;
                }
                //0 move
                if (action.type == 1 && action.measure == 0) {
                    return true;
                }
                return false;
            }

            //@TODO DRY this up
            for (var j = 0; j < redGoatActions.length; j++) {
                if (isNothingAction(redGoatActions[j])) {
                    continue;
                }
                setTimeout(function(index, action, goat, that, i) { return function() {
                    app.viewRound = i+1;
                    doAction(action, that, 'RED');
                }; }(j, redGoatActions[j], this.redGoat, this, i), timeTracker);
                timeTracker += this.actionDuration;
            }

            for (var k = 0; k < blueGoatActions.length; k++) {
                if (isNothingAction(blueGoatActions[k])) {
                    continue;
                }
                setTimeout(function(index, action, goat, that, i) { return function() {
                    app.viewRound = i+1;
                    doAction(action, that, 'BLUE');
                }; }(k, blueGoatActions[k], this.blueGoat, this, i), timeTracker);
                timeTracker += this.actionDuration;
            }

        }
        setTimeout( function(){ console.log('The End'); }, timeTracker);
    },
    displayOutcome : function() {
        return (this.viewRound == this.battle.battleTranscript.length) ? this.battle.outcomesMap[this.battle.outcome] : null;
    }
  },
  computed : {
  }
});