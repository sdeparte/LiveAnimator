export default class Coins {
    retina = window.devicePixelRatio;

    loaded = false;
    interval = null;

    constructor(id) {
        this.canvas = document.getElementById(id);
        this.context = this.canvas.getContext('2d');

        const canvasParent = this.canvas.parentNode;

        this.canvas.width = canvasParent.offsetWidth * this.retina;
        this.canvas.height = canvasParent.offsetHeight * this.retina;

        this.coin = new Image();
        this.coin.src = 'http://i.imgur.com/5ZW2MT3.png';

        this.coin.onload = function () {
            this.loaded = true;
        }.bind(this)

        this.coins = [];
    }

    start = function () {
        if (this.loaded) {
            this.update();
        } else {
            this.coin.onload = function () {
                this.loaded = true;
                this.update();
            }.bind(this)
        }
    }

    stop = function () {
        if (null !== this.interval) {
            cancelAnimationFrame(this.interval);
        }

        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    update = function () {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

        if (this.loaded) {
            this.interval = requestAnimationFrame(this.update.bind(this));

            if (Math.random() < .3) {
                this.coins.push({
                    x: Math.random() * this.canvas.width | 0,
                    y: -50,
                    dy: 3,
                    s: 0.5 + Math.random(),
                    state: Math.random() * 10 | 0
                });
            }

            let i = this.coins.length;

            while (i--) {
                const x = this.coins[i].x;
                const y = this.coins[i].y;
                const s = this.coins[i].s;
                const state = this.coins[i].state;

                this.coins[i].state = (state > 9) ? 0 : state + 0.1;
                this.coins[i].dy += 0.05;
                this.coins[i].y += this.coins[i].dy;

                this.context.drawImage(this.coin, 44 * Math.floor(state), 0, 44, 40, x, y, 44 * s, 40 * s);

                if (y > this.canvas.height) {
                    this.coins.splice(i, 1);
                }
            }
        }
    }
}