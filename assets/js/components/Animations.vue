<template>
  <div id="animation" class="hidden">
    <canvas height="1" id="confettis" width="1"></canvas>
    <canvas height="1" id="coins" width="1"></canvas>
    <canvas height="1" id="firework" width="1"></canvas>
    <div id="username">
      <div v-html="message"></div>
    </div>
  </div>
</template>

<script>
import Confettis from "../classes/Confettis";
import Coins from "../classes/Coins";
import Firework from "../classes/Firework";

export default {
  name: "animations",
  data() {
    return {message: '', confettis: null, coins: null, firework: null, animationIsRunning: false, nextEventRecieved: []}
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      this.confettis = new Confettis("confettis");
      this.coins = new Coins("coins");
      this.firework = new Firework("firework");

      const url = new URL("/.well-known/mercure", window.origin);
      url.searchParams.append('topic', "http://example.com/events");

      const eventSource = new EventSource(url);
      eventSource.onmessage = event => {
        this.animate(JSON.parse(event.data));
      }
    },
    animate(data) {
      if (this.animationIsRunning) {
        this.nextEventRecieved.push(data);
      } else {
        this.animationIsRunning = true;

        document.getElementById("animation").className = "";

        switch (data.type) {
          case 'follow':
            this.message = "Merci <span style=\"font-weight: bold;\">" + data.username + "</span> pour ton follow !";

            this.firework.start();

            setTimeout(function () {
              document.getElementById("animation").className = "hidden";

              setTimeout(function () {
                this.firework.stop();

                this.animateNext();
              }.bind(this), 1000);
            }.bind(this), 5000);

            break;
          case 'subscribe':
            this.message = "Merci <span style=\"font-weight: bold;\">" + data.username + "</span> pour ton abonnement !";

            this.confettis.start();

            setTimeout(function () {
              document.getElementById("animation").className = "hidden";

              setTimeout(function () {
                this.confettis.stop();

                this.animateNext();
              }.bind(this), 1000);
            }.bind(this), 5000);

            break;
          case 'donation':
            this.message = "Merci <span style=\"font-weight: bold;\">" + data.username + "</span> pour ta donation de <span style=\"font-weight: bold;\">" + data.amount + "</span> !";
            this.coins.start();

            setTimeout(function () {
              document.getElementById('animation').className = 'hidden';

              setTimeout(function () {
                this.coins.stop();

                this.animateNext();
              }.bind(this), 1000);
            }.bind(this), 5000);

            break;
        }
      }
    },
    animateNext() {
      this.animationIsRunning = false;

      let nextEvent = this.nextEventRecieved.shift();

      if (undefined !== nextEvent) {
        this.animate(nextEvent);
      }
    },
  }
}
</script>

<style scoped>
  #animation {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    overflow: hidden;
    background: linear-gradient(0deg, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0) 25%);

    transition: opacity 1s ease-in-out;
  }

  #animation.hidden {
    opacity: 0;
  }

  #animation > canvas {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
  }

  #animation > #username {
    position: absolute;
    border-radius: 90px;
    padding: 20px;
    left: 50%;
    bottom: 25px;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.25);

    transition: bottom 1s ease-in-out;
  }

  #animation > #username > div {
    font-family: Verdana, Geneva, sans-serif;
    border-radius: 50px;
    padding: 20px 50px;
    height: 50px;
    line-height: 50px;
    font-size: 25px;
    background: white;
    text-align: center;
  }

  #animation.hidden > #username {
    bottom: -50px;
  }
</style>