import Vector2 from "./Vector2";
import EulerMass from "./EulerMass";

export default class ConfettiRibbon {
    retina = window.devicePixelRatio;

    constructor(_colors, _bounds, _x, _y, _count, _dist, _thickness, _angle, _mass, _drag) {
        this.colors = _colors;
        this.bounds = _bounds;
        this.particleDist = _dist;
        this.particleCount = _count;
        this.particleMass = _mass;
        this.particleDrag = _drag;
        this.particles = [];

        let ci = Math.round(Math.random() * (this.colors.length - 1));

        this.frontColor = this.colors[ci][0];
        this.backColor = this.colors[ci][1];
        this.xOff = (Math.cos((Math.PI / 180) * _angle) * _thickness);
        this.yOff = (Math.sin((Math.PI / 180) * _angle) * _thickness);
        this.position = new Vector2(_x, _y);
        this.prevPosition = new Vector2(_x, _y);
        this.velocityInherit = (Math.random() * 2 + 4);
        this.time = Math.random() * 100;
        this.oscillationSpeed = (Math.random() * 2 + 2);
        this.oscillationDistance = (Math.random() * 40 + 40);
        this.ySpeed = (Math.random() * 40 + 80);

        for (let i = 0; i < this.particleCount; i++) {
            this.particles[i] = new EulerMass(_x, _y - i * this.particleDist, this.particleMass, this.particleDrag);
        }
    }

    Vector2_Sub = function (_vec0, _vec1) {
        return new Vector2(_vec0.x - _vec1.x, _vec0.y - _vec1.y, _vec0.z - _vec1.z);
    }

    Update = function (_dt) {
        let i = 0;

        this.time += _dt * this.oscillationSpeed;
        this.position.y += this.ySpeed * _dt;
        this.position.x += Math.cos(this.time) * this.oscillationDistance * _dt;
        this.particles[0].position = this.position;

        let dX = this.prevPosition.x - this.position.x;
        let dY = this.prevPosition.y - this.position.y;
        let delta = Math.sqrt(dX * dX + dY * dY);

        this.prevPosition = new Vector2(this.position.x, this.position.y);

        for (i = 1; i < this.particleCount; i++) {
            let dirP = this.Vector2_Sub(this.particles[i - 1].position, this.particles[i].position);

            dirP.Normalize();
            dirP.Mul((delta / _dt) * this.velocityInherit);
            this.particles[i].AddForce(dirP);
        }

        for (i = 1; i < this.particleCount; i++) {
            this.particles[i].Integrate(_dt);
        }

        for (i = 1; i < this.particleCount; i++) {
            let rp2 = new Vector2(this.particles[i].position.x, this.particles[i].position.y);

            rp2.Sub(this.particles[i - 1].position);
            rp2.Normalize();
            rp2.Mul(this.particleDist);
            rp2.Add(this.particles[i - 1].position);
            this.particles[i].position = rp2;
        }

        if (this.position.y > this.bounds.y + this.particleDist * this.particleCount) {
            this.Reset();
        }
    }

    Reset = function () {
        this.position.y = -Math.random() * this.bounds.y;
        this.position.x = Math.random() * this.bounds.x;
        this.prevPosition = new Vector2(this.position.x, this.position.y);
        this.velocityInherit = Math.random() * 2 + 4;
        this.time = Math.random() * 100;
        this.oscillationSpeed = Math.random() * 2.0 + 1.5;
        this.oscillationDistance = (Math.random() * 40 + 40);
        this.ySpeed = Math.random() * 40 + 80;

        let ci = Math.round(Math.random() * (this.colors.length - 1));

        this.frontColor = this.colors[ci][0];
        this.backColor = this.colors[ci][1];
        this.particles = [];

        for (let i = 0; i < this.particleCount; i++) {
            this.particles[i] = new EulerMass(this.position.x, this.position.y - i * this.particleDist, this.particleMass, this.particleDrag);
        }
    }

    Draw = function (_g) {
        for (let i = 0; i < this.particleCount - 1; i++) {
            let p0 = new Vector2(this.particles[i].position.x + this.xOff, this.particles[i].position.y + this.yOff);
            let p1 = new Vector2(this.particles[i + 1].position.x + this.xOff, this.particles[i + 1].position.y + this.yOff);

            if (this.Side(this.particles[i].position.x, this.particles[i].position.y, this.particles[i + 1].position.x, this.particles[i + 1].position.y, p1.x, p1.y) < 0) {
                _g.fillStyle = this.frontColor;
                _g.strokeStyle = this.frontColor;
            } else {
                _g.fillStyle = this.backColor;
                _g.strokeStyle = this.backColor;
            }
            if (0 === i) {
                _g.beginPath();
                _g.moveTo(this.particles[i].position.x * this.retina, this.particles[i].position.y * this.retina);
                _g.lineTo(this.particles[i + 1].position.x * this.retina, this.particles[i + 1].position.y * this.retina);
                _g.lineTo(((this.particles[i + 1].position.x + p1.x) * 0.5) * this.retina, ((this.particles[i + 1].position.y + p1.y) * 0.5) * this.retina);
                _g.closePath();
                _g.stroke();
                _g.fill();
                _g.beginPath();
                _g.moveTo(p1.x * this.retina, p1.y * this.retina);
                _g.lineTo(p0.x * this.retina, p0.y * this.retina);
                _g.lineTo(((this.particles[i + 1].position.x + p1.x) * 0.5) * this.retina, ((this.particles[i + 1].position.y + p1.y) * 0.5) * this.retina);
                _g.closePath();
                _g.stroke();
                _g.fill();
            } else if (this.particleCount - 2 === i) {
                _g.beginPath();
                _g.moveTo(this.particles[i].position.x * this.retina, this.particles[i].position.y * this.retina);
                _g.lineTo(this.particles[i + 1].position.x * this.retina, this.particles[i + 1].position.y * this.retina);
                _g.lineTo(((this.particles[i].position.x + p0.x) * 0.5) * this.retina, ((this.particles[i].position.y + p0.y) * 0.5) * this.retina);
                _g.closePath();
                _g.stroke();
                _g.fill();
                _g.beginPath();
                _g.moveTo(p1.x * this.retina, p1.y * this.retina);
                _g.lineTo(p0.x * this.retina, p0.y * this.retina);
                _g.lineTo(((this.particles[i].position.x + p0.x) * 0.5) * this.retina, ((this.particles[i].position.y + p0.y) * 0.5) * this.retina);
                _g.closePath();
                _g.stroke();
                _g.fill();
            } else {
                _g.beginPath();
                _g.moveTo(this.particles[i].position.x * this.retina, this.particles[i].position.y * this.retina);
                _g.lineTo(this.particles[i + 1].position.x * this.retina, this.particles[i + 1].position.y * this.retina);
                _g.lineTo(p1.x * this.retina, p1.y * this.retina);
                _g.lineTo(p0.x * this.retina, p0.y * this.retina);
                _g.closePath();
                _g.stroke();
                _g.fill();
            }
        }
    }

    Side = function (x1, y1, x2, y2, x3, y3) {
        return ((x1 - x2) * (y3 - y2) - (y1 - y2) * (x3 - x2));
    }
}