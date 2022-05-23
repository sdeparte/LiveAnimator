<template>
<div class="player">
    <div class="player__card" :class="{ 'disk-changing': diskChanging }">
        <div class="player__album rotating">
            <div class="player__albumImg" :style="{backgroundImage:`url(${albumImg})`}"></div>           
        </div>
        <div class="player__bars">
            <div class="player__bars__container" :class="{ 'no-sound': noSound }">
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
                <div class="player__bars__container__bar"></div>
            </div>
        </div>
        <div class="player__timeline">
                <p class="player__author">{{ author }}</p>
                <p class="player__song">{{ song }}</p>
        </div>
    </div>
</div>
</template>

<script>
export default {
  name: "what-the-song",
  data() {
    return {noSound: true, albumImg: 'https://www.formica.com/fr-fr/-/media/formica/emea/products/swatch-images/f2253/f2253-swatch.jpg', author: 'Sylvain D', song: 'The silence'}
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
        this.changeCurrentSong(JSON.parse(event.data));
      }
    },
    changeCurrentSong(data) {
      if (data.type == 'music_no_sound') {
        this.noSound = data.noSound;
      } else if (data.type == 'music') {
        this.author = '';
        this.song = '';
        this.noSound = data.noSound;
        this.diskChanging = true;

        setTimeout(function() {
            this.albumImg = data.albumImg;
        }.bind(this), 500);
        setTimeout(function() {
            this.author = data.author;
            this.song = data.song;
            this.diskChanging = false;
        }.bind(this), 1000);
      }
    },
  }
}
</script>

<style lang="scss" scoped>
@function randomNum($min, $max) {
    $rand: random();
    $randomNum: $min + floor($rand * (($max - $min) + 1));

    @return $randomNum;
}

@-webkit-keyframes rotating /* Safari and Chrome */ {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes rotating {
    from {
        transform: rotate(-20deg);
    }
    to {
        transform: rotate(340deg);
  }
}

@keyframes sound {
    0% {
        opacity: .35;
        height: 3px; 
    }
    100% {
        opacity: 1;
        height: 135px;
    }
}

@keyframes changeDisk {
    0% {
        transform: scale(1);
    }
    25% {
        transform: scale(1.2);
    }
    50% {
        transform: scaleX(1.2) rotateX(90deg);
    }
    75% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

.player__card.disk-changing > .player__album {
    animation: changeDisk 1s ease-in-out;
}

.player__card.disk-changing > .player__bars > .player__bars__container,
.player__card > .player__bars > .player__bars__container.no-sound {
    transform: translateY(73px) scaleY(0);
    opacity: 0;
}

.player {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    position: absolute;
    top: 12px;
    left: 0;
    &__album {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        margin-right: 22px;
        position: relative;
        z-index: 4;
        top: -25px;
        -webkit-mask-image: url(https://i.imgur.com/J6YhBPc.png);
        mask-image: url(https://i.imgur.com/J6YhBPc.png);
        -webkit-mask-size: 180px 180px;
        mask-size: 180px 180px;
        &:before {
            content: '';
            width: 50px;
            height: 50px;
            position: absolute;
            z-index: 5;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
        }
    }
    &__albumImg {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        position: relative;
        z-index: 4;
        display: block;
        animation: rotating 10s linear infinite;
    }
    &__card {
        background: rgba(0, 0, 0, 0.6);
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.75);
        padding: 5px 20px;
        height: 135px;
        display: flex;
        justify-content: space-between;
        border-radius: 0 75px 75px 0;
        position: relative;
    }
    &__timeline {
        width: 400px;
        height: 135px;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        position: relative;
        z-index: 5;
    }
    &__author {
        color: #fff;
        line-height: 1;
        font-weight: bold;
        font-size: 30px;
        margin-top: 0;
        margin-bottom: 6px;
    }
    &__song {
        color: #eee;
        line-height: 1;
        font-size: 28px;
        margin: 0;
    }
    &__bars {
        position: absolute;
        bottom: 0;
        top: 0;
        left: 0;
        right: 0;
        border-radius: 0 75px 75px 0;
        overflow: hidden;
        &__container {
            height: 145px;
            transition: all .5s ease-in-out;
            &__bar {
                background: rgba(0, 0, 0, 0.2);
                bottom: 0px;
                height: 3px;
                position: absolute;
                width: 25px;
                z-index: 3;
                animation: sound 0ms -800ms linear infinite alternate;
            }
            @for $i from 0 through 25 {
                &__bar:nth-child(#{$i + 1}) {
                    left: 26px * $i - 5px;
                    animation-duration: #{randomNum(300, 800)}ms;
                }
            }
        }
    }
}
</style>
