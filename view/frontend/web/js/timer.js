define(['uiComponent', 'ko'], function (Component, ko) {
    return Component.extend({

        defaults: {
            timerInterval: -1,
            hour: 0,
            minutes: 0,
            seconds: 0,
            hourDisplay: "",
            minutesDisplay: "",
            secondsDisplay: "",
            timerLabel: "00:00:00"
        },

        initObservable: function () {
            this._super();
            this.observe(['hourDisplay', 'minutesDisplay', 'secondsDisplay', 'timerLabel']);
            return this;
        },

        displayTimer: function () {
            this.seconds++;
            if (this.seconds == 60) {
                this.minutes++;
                this.seconds = 0;
            }
            if (this.minutes == 60) {
                this.hour++;
                this.minutes = 0;
            }

            this.hourDisplay = this.hour;
            this.minutesDisplay = this.minutes;
            this.secondsDisplay = this.seconds;

            if (this.hour < 10) { this.hourDisplay = '0' + this.hour};
            if (this.minutes < 10) { this.minutesDisplay = '0' + this.minutes };
            if (this.seconds < 10) { this.secondsDisplay = '0' + this.seconds };
            this.timerLabel(this.hourDisplay + ":" + this.minutesDisplay + ":" + this.secondsDisplay);
        },

        clearTimer: function() {
            this.hour = 0 , this.minutes = 0, this.seconds = 0,
                this.hourDisplay = "", this.minutesDisplay = "", this.secondsDisplay = "";
        },

        startTimer: function () {
            if (this.timerInterval == -1) {
                this.timerInterval = setInterval(
                    function () { this.displayTimer(); }.bind(this), 1000);
            }
        },

        stopTimer: function () {
            this.clearTimer();
            clearInterval(this.timerInterval);
            this.timerInterval = -1;
            this.timerLabel('00:00:00');
        },

        pauseTimer: function () {
            if (this.timerInterval != -1) {
                clearInterval(this.timerInterval);
                this.timerInterval = -1;
            }
        }

    });
});
