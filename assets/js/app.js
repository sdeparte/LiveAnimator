// assets/js/app.js
import Vue from 'vue';

import Animations from "./components/Animations";
import WhatTheSong from "./components/WhatTheSong";
import Workout from "./components/Workout";

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#app',
    components: {Animations, WhatTheSong, Workout}
});
