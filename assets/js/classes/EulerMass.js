import Vector2 from "./Vector2";

export default class EulerMass {
    constructor(_x, _y, _mass, _drag) {
        this.position = new Vector2(_x, _y);
        this.mass = _mass;
        this.drag = _drag;
        this.force = new Vector2(0, 0);
        this.velocity = new Vector2(0, 0);
    }

    AddForce = function (_f) {
        this.force.Add(_f);
    }

    Integrate = function (_dt) {
        let acc = this.CurrentForce(this.position);

        acc.Div(this.mass);

        let posDelta = new Vector2(this.velocity.x, this.velocity.y);

        posDelta.Mul(_dt);
        this.position.Add(posDelta);
        acc.Mul(_dt);
        this.velocity.Add(acc);
        this.force = new Vector2(0, 0);
    }

    CurrentForce = function (_pos, _vel) {
        let totalForce = new Vector2(this.force.x, this.force.y);
        let speed = this.velocity.Length();
        let dragVel = new Vector2(this.velocity.x, this.velocity.y);

        dragVel.Mul(this.drag * this.mass * speed);
        totalForce.Sub(dragVel);

        return totalForce;
    }
}