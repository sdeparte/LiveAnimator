<template>
<div class="progressbar">
  <div class="container" :class="{ 'visible': visible }">
    <div class="shadow"></div>
    <div class="content" v-html="description"></div>
    <div id="progressbar" class="bar" :class="{ 'animation-infinite': animation == 'infinite', 'animation-once': animation == 'once' }" :style="{ 'animation-duration': duration, 'animation-play-state': state }"></div>
  </div>
</div>
</template>

<script>
export default {
  name: "workout",
  data() {
    return {decription: '', visible: false, animation: 'none', duration: '1s', state: 'running'}
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      const url = new URL(process.env.MERCURE_PUBLIC_URL, window.origin);
      url.searchParams.append('topic', "http://example.com/events");

      const eventSource = new EventSource(url, {withCredentials: true});
      eventSource.onmessage = event => {
        this.changeCurrentExercice(JSON.parse(event.data));
      }
    },
    changeCurrentExercice(data) {
      if (data.type == 'workout_pause') {
        this.state = data.pause ? 'paused' : 'running'; 
      } else if (data.type == 'workout') {
        this.visible = false;
  
        setTimeout(function() {
          this.animation = 'none';
        }.bind(this), 500);

        if (data.visible) {
          setTimeout(function() {
            if (data.duration != null) {
              if (data.isRest) {
                this.description = `Temps de repos :<br />${data.duration} seconde(s)`;
              } else if (data.series != null) {
                this.description = `${data.exerciceName} (série ${data.series}) :<br />${data.duration} seconde(s)`;
              } else {
                this.description = `${data.exerciceName} :<br />${data.duration} seconde(s)`;
              }

              this.duration = `${data.duration - 0.75}s`;
              this.animation = 'once';
            } else if (data.repetitions != null) {
              if (data.series != null) {
                this.description = `${data.exerciceName} (série ${data.series}) :<br />${data.repetitions} répétition(s)`;
              } else {
                this.description = `${data.exerciceName} :<br />${data.repetitions} répétition(s)`;
              }

              this.duration = '1s';
              this.animation = 'infinite';
            }

            this.visible = true;
          }.bind(this), 750);
        }
      }
    },
  }
}
</script>

<style lang="scss" scoped>
@keyframes loading {
    0% {
      width: 0%;
      background: #32ff7e;
      color: #32ff7e;
    }
    50% {
      background: #32ff7e;
      color: #32ff7e;
    }
    75% {
      background: #ff9f1a;
      color: #ff9f1a;
    }
    100% {
      width: 100%;
      background: #ff3838;
      color: #ff3838;
    }
}

@keyframes loadingInfinite {
    0% {
      width: 0%;
      background: #32ff7e;
      color: #32ff7e;
    }
    25% {
      background: #c56cf0;
      color: #c56cf0;
    }
    50% {
      width: 100%;
      left: 0%;
      background-color: #18dcff;
      color: #18dcff;
    }
    75% {
      background: #fff200;
      color: #fff200;
    }
    100% {
      width: 0%;
      left: 100%;
      background: #ff3838;
      color: #ff3838;
    }
}

.progressbar {
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  color: white;
  overflow: hidden;
  
  .container {
    position: absolute;
    left: -3px;
    bottom: -4px;
    right: -3px;
    opacity: 0;
    transform: translateY(250px);
    transition: opacity .5s ease-in-out, transform .75s ease-in-out;
      
    &.visible {
      transform: translateY(0px);
      opacity: 1;
    }

    .bar {
      position: relative;
      box-shadow: 0 0 10px 1px;
      height: 5px;
      border-radius: 5px;
      display: inline-block;
      z-index: 2;

      &.animation-infinite {
        animation: 1s linear 0ms infinite normal none running loadingInfinite;
      }
      
      &.animation-once {
        animation: 10s linear 0ms 1 normal forwards running loading;
      }
    }

    .shadow {
      position: absolute;
      width: 100%;
      box-shadow: 0 0 200px 200px rgba(0, 0, 0, .65);
    }

    .content {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      bottom: 25px;
      font-size: 1.75em;
      text-align: center;
      text-shadow: 0 0 1px rgba(0, 0, 0, .5);
    }
  }
}
</style>
