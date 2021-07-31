export default class Vector2 {
    constructor(_x, _y) {
        this.x = _x;
        this.y = _y;
    }

    Length = function () {
        return Math.sqrt(this.SqrLength());
    }

    SqrLength = function () {
        return this.x * this.x + this.y * this.y;
    }

    Add = function (_vec) {
        this.x += _vec.x;
        this.y += _vec.y;
    }

    Sub = function (_vec) {
        this.x -= _vec.x;
        this.y -= _vec.y;
    }

    Div = function (_f) {
        this.x /= _f;
        this.y /= _f;
    }

    Mul = function (_f) {
        this.x *= _f;
        this.y *= _f;
    }

    Normalize = function () {
        let sqrLen = this.SqrLength();

        if (0 !== sqrLen) {
            let factor = 1.0 / Math.sqrt(sqrLen);

            this.x *= factor;
            this.y *= factor;
        }
    }

    Normalized = function () {
        let sqrLen = this.SqrLength();

        if (0 !== sqrLen) {
            let factor = 1.0 / Math.sqrt(sqrLen);

            return new Vector2(this.x * factor, this.y * factor);
        }

        return new Vector2(0, 0);
    }
}