import Vector2 from "./Vector2";

export default class ConfettiPaper {
    retina = window.devicePixelRatio;

    constructor(_colors, _bounds, _x, _y) {
        this.colors = _colors;
        this.bounds = _bounds;
        this.pos = new Vector2(_x, _y);
        this.rotationSpeed = (Math.random() * 600 + 800);
        this.angle = (Math.PI / 180) * Math.random() * 360;
        this.rotation = (Math.PI / 180) * Math.random() * 360;
        Math.cosA = 1.0;
        this.size = 10.0;
        this.oscillationSpeed = (Math.random() * 1.5 + 0.5);
        this.xSpeed = 40.0;
        this.ySpeed = (Math.random() * 60 + 50.0);
        this.corners = [];
        this.time = Math.random();

        let ci = Math.round(Math.random() * (this.colors.length - 1));
        this.frontColor = this.colors[ci][0];
        this.backColor = this.colors[ci][1];

        for (let i = 0; i < 4; i++) {
            let dx = Math.cos(this.angle + (Math.PI / 180) * (i * 90 + 45));
            let dy = Math.sin(this.angle + (Math.PI / 180) * (i * 90 + 45));

            this.corners[i] = new Vector2(dx, dy);
        }
    }

    Update = function (_dt) {
        this.time += _dt;
        this.rotation += this.rotationSpeed * _dt;
        Math.cosA = Math.cos((Math.PI / 180) * this.rotation);
        this.pos.x += Math.cos(this.time * this.oscillationSpeed) * this.xSpeed * _dt
        this.pos.y += this.ySpeed * _dt;

        if (this.pos.y > this.bounds.y) {
            this.pos.x = Math.random() * this.bounds.x;
            this.pos.y = 0;
        }
    }

    Draw = function (_g) {
        if (Math.cosA > 0) {
            _g.fillStyle = this.frontColor;
        } else {
            _g.fillStyle = this.backColor;
        }

        _g.beginPath();
        _g.moveTo((this.pos.x + this.corners[0].x * this.size) * this.retina, (this.pos.y + this.corners[0].y * this.size * Math.cosA) * this.retina);

        for (var i = 1; i < 4; i++) {
            _g.lineTo((this.pos.x + this.corners[i].x * this.size) * this.retina, (this.pos.y + this.corners[i].y * this.size * Math.cosA) * this.retina);
        }

        _g.closePath();
        _g.fill();
    }
}