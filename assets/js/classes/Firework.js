import FireworkParticle from "./FireworkParticle";

export default class Firework {
    active = false;

    constructor(id) {
        this.width = window.innerWidth;
        this.height = window.innerHeight;

        this.particles = [];

        this.canvas = document.getElementById(id);
        this.canvas.width = this.width;
        this.canvas.height = this.height;

        this.context = this.canvas.getContext('2d');
    }

    start = function () {
        this.active = true;

        this.create();
        this.loop();
    }

    stop = function () {
        this.active = false;
    };

    create = function () {
        if (!this.active || this.particles.length > 4) {
            return
        }

        this.particles.push( new FireworkParticle(this.context, this.width, this.height, 100));
        setTimeout(this.create.bind(this), 600);
    };

    loop = function () {
        this.context.clearRect( 0, 0, this.width, this.height );

        if (this.active) {
            requestAnimationFrame(this.loop.bind(this));

            for (let p of this.particles) {
                if (p.complete()) {
                    p.reset();
                }

                p.update(this.width, this.height);
                p.draw();
            }
        } else {
            cancelAnimationFrame(this.loop);
        }
    };
};