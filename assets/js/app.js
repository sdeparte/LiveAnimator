// assets/js/app.js
import Vue from 'vue';

import Confettis from './components/Confettis'
import Coins from './components/Coins'

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#app',
    components: {Confettis, Coins}
});