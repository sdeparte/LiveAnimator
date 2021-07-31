import Vector2 from "./Vector2";
import ConfettiRibbon from "./ConfettiRibbon";
import ConfettiPaper from "./ConfettiPaper";

export default class Confettis {
    retina = window.devicePixelRatio;

    // Configuration variables
    speed = 50;
    duration = (1.0 / this.speed);
    confettiRibbonCount = 11;
    ribbonPaperCount = 30;
    ribbonPaperDist = 16.0;
    ribbonPaperThick = 16.0;
    confettiPaperCount = 95;
    colors = [
        ["#df0049", "#660671"],
        ["#00e857", "#005291"],
        ["#2bebbc", "#05798a"],
        ["#ffd200", "#b06c00"]
    ];

    interval = null;

    constructor(id) {
        let i = 0;
        this.canvas = document.getElementById(id);
        this.canvasParent = this.canvas.parentNode;

        let canvasWidth = this.canvasParent.offsetWidth;
        let canvasHeight = this.canvasParent.offsetHeight;

        this.canvas.width = canvasWidth * this.retina;
        this.canvas.height = canvasHeight * this.retina;

        this.context = this.canvas.getContext('2d');

        this.confettiRibbons = [];

        for (i = 0; i < this.confettiRibbonCount; i++) {
            this.confettiRibbons[i] = new ConfettiRibbon(this.colors, new Vector2(canvasWidth, canvasHeight), Math.random() * canvasWidth, -Math.random() * canvasHeight * 2, this.ribbonPaperCount, this.ribbonPaperDist, this.ribbonPaperThick, 45, 1, 0.05);
        }

        this.confettiPapers = [];

        for (i = 0; i < this.confettiPaperCount; i++) {
            this.confettiPapers[i] = new ConfettiPaper(this.colors, new Vector2(canvasWidth, canvasHeight), Math.random() * canvasWidth, Math.random() * canvasHeight);
        }
    }

    start = function () {
        this.stop();
        this.update();
    }

    stop = function () {
        if (null !== this.interval) {
            cancelAnimationFrame(this.interval);
        }

        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    update = function () {
        let i = 0;

        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

        for (i = 0; i < this.confettiPaperCount; i++) {
            this.confettiPapers[i].Update(this.duration);
            this.confettiPapers[i].Draw(this.context);
        }

        for (i = 0; i < this.confettiRibbonCount; i++) {
            this.confettiRibbons[i].Update(this.duration);
            this.confettiRibbons[i].Draw(this.context);
        }

        this.interval = requestAnimationFrame(this.update.bind(this));
    }
}