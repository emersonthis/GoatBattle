Vue.component('goat', {
  template: '<svg v-bind:class="rammingClass" :style="makeStyle()" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="283.46px" height="283.465px" viewBox="0 274.045 283.46 283.465" enable-background="new 0 274.045 283.46 283.465" xml:space="preserve"><polygon class="standing" points="26.411,378.428 173.238,378.428 210.938,357.735 212.356,327.973 203.001,315.783 161.616,303.311 206.12,303.311 234.465,323.721 258.276,381.546 258.276,398.837 229.364,383.814 190.813,474.805 210.938,532.914 198.182,532.914 157.647,460.064 75.166,442.773 22.727,495.78 16.207,532.914 3.451,532.914 3.451,488.694 26.411,425.199 "/><polygon class="ramming" points="61.041,384.871 198.833,389.133 240.258,377.584 262.164,349.523 260.575,334.24 231.845,301.947 269.762,325.247 283.227,357.477 273.237,419.21 264.184,433.941 252.25,407.178 205.975,486.978 165.537,534.185 153.117,534.278 177.045,465.062 93.835,458.641 30.689,498.291 15.872,532.959 3.453,533.053 13.538,486.995 50.374,430.41 "/></svg>',
  props: ['x','y','direction', 'isRamming'],
  methods : {
    makeStyle : function() {
        var x = (this.x + 50);
        var y = (this.y + 50);
        var flip = (this.direction > 90 == this.direction < 270) ? ' scaleY(-1)' : '';        
        return "left:"+x+"%;bottom:"+y+"%;transform:rotate(-"+this.direction+"deg)"+flip+";";
    }
  },
  computed : {
    rammingClass : function() {
        return (this.isRamming) ? 'isRamming' : '';
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
        name: battle.goat1.name,
        isRamming : false
    },
    blueGoat: {
        x: 50,
        y: -50,
        direction: 135,
        health: battle.goat2.toughness,
        name: battle.goat2.name,
        isRamming : false
    }
  },
  methods : {
    // goatHealth : function(redOrBlue) {
    //     if (this.viewRound) {
    //         return (redOrBlue == 'RED') ? battle.battleTranscript[this.viewRound - 1].redGoat.health : battle.battleTranscript[this.viewRound - 1].blueGoat.health;
    //     }
    //     return (redOrBlue == 'RED') ? battle.battleTranscript[0].redGoat.health : battle.battleTranscript[0].blueGoat.health;
    // },
    showRam : function(redOrBlue) {
        if (redOrBlue == 'RED') {
            app.redGoat.isRamming = true;
            setTimeout(function(){ app.redGoat.isRamming = false; }, this.actionDuration);
        } else {
            app.blueGoat.isRamming = true;
            setTimeout(function(){ app.blueGoat.isRamming = false; }, this.actionDuration);
        }
    },
    startBattle : function() {
        console.log('Start!');

        var timeTracker = 0; //ms of battle duration

        for (var i=0; i<this.battle.battleTranscript.length; i++) {

            var redGoatActions = this.battle.battleTranscript[i].redGoatActions;
            var blueGoatActions = this.battle.battleTranscript[i].blueGoatActions;

            //@TODO We also need to update the goats' health so that we can see the effects of a ram
            var doAction = function(action, that, redOrBlue) {

                if (action.type == 3) {
                    app.showRam(redOrBlue);
                }

                if (redOrBlue == 'RED') {
                    app.redGoat.x = action.endSituation.redLocation.x;
                    app.redGoat.y = action.endSituation.redLocation.y;
                    app.redGoat.direction = action.endSituation.redLocation.direction;
                    app.blueGoat.health = action.endSituation.blueGoat.health;
                } else {
                    app.blueGoat.x = action.endSituation.blueLocation.x;
                    app.blueGoat.y = action.endSituation.blueLocation.y;
                    app.blueGoat.direction = action.endSituation.blueLocation.direction;
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