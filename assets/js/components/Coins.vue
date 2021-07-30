<template>
  <div id="new-donator" class="hidden">
    <canvas height="1" id="coins" width="1"></canvas>
    <div id="username">
      <div>
        Merci <span>{{ value }}</span> pour le don !
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "coins",
  data() {
    return {value: ''}
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      var focused = false;

      function startFallingCoins() {
        var retina = window.devicePixelRatio;
        var canvas = document.getElementById("coins");
        var ctx = canvas.getContext('2d');
        var canvasParent = canvas.parentNode;

        canvas.width = canvasParent.offsetWidth * retina;
        canvas.height = canvasParent.offsetHeight * retina;

        var coin = new Image();
        coin.src = 'http://i.imgur.com/5ZW2MT3.png'

        coin.onload = function () {
          focused = true;
          drawloop();
        }
        var coins = []

        function drawloop() {
          ctx.clearRect(0, 0, canvas.width, canvas.height)

          if (focused) {
            requestAnimationFrame(drawloop);

            if (Math.random() < .3) {
              coins.push({
                x: Math.random() * canvas.width | 0,
                y: -50,
                dy: 3,
                s: 0.5 + Math.random(),
                state: Math.random() * 10 | 0
              })
            }
            var i = coins.length
            while (i--) {
              var x = coins[i].x
              var y = coins[i].y
              var s = coins[i].s
              var state = coins[i].state
              coins[i].state = (state > 9) ? 0 : state + 0.1
              coins[i].dy += 0.05
              coins[i].y += coins[i].dy

              ctx.drawImage(coin, 44 * Math.floor(state), 0, 44, 40, x, y, 44 * s, 40 * s)

              if (y > canvas.height) {
                coins.splice(i, 1);
              }
            }
          } else {
            cancelAnimationFrame(drawloop);
          }
        }

      }

      const url = new URL('/.well-known/mercure', window.origin);
      url.searchParams.append('topic', 'http://example.com/coins');

      const eventSource = new EventSource(url);
      eventSource.onmessage = event => {
        document.getElementById('new-donator').className = '';
        this.value = JSON.parse(event.data).username;

        startFallingCoins();

        setTimeout(function () {
          document.getElementById('new-donator').className = 'hidden';

          setTimeout(function () {
            focused = false;
          }, 1000);
        }, 5000);
      }
    },
  }
}
</script>

<style scoped>
  #new-donator {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    overflow: hidden;

    transition: opacity 1s ease-in-out;
  }

  #new-donator.hidden {
    opacity: 0;
  }

  #new-donator > #username {
    position: absolute;
    border-radius: 90px;
    padding: 20px;
    left: 50%;
    bottom: 25px;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.25);

    transition: bottom 1s ease-in-out;
  }

  #new-donator > #username > div {
    font-family: Verdana, Geneva, sans-serif;
    border-radius: 50px;
    padding: 20px 50px;
    height: 50px;
    line-height: 50px;
    font-size: 25px;
    background: white;
    text-align: center;
  }

  #new-donator > #username > div > span {
    font-weight: bold;
  }

  #new-donator.hidden > #username {
    bottom: -50px;
  }
</style>