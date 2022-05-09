// assets/js/app.js
import Vue from 'vue';

import Animations from "./components/Animations";
import WhatTheSong from "./components/WhatTheSong";

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#app',
    components: {Animations, WhatTheSong}
});
