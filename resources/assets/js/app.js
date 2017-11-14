
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

var ApiRequest  = require('./classes/ApiRequest');
var Dispatcher  = require('./classes/Dispatcher');
var Locale      = require('./classes/Locale');
var Config      = require('./classes/Config');

require('./bootstrap');
require('./classes/Errors');
require('./classes/Form');
require('./components');
require('babel-core/register');
require('babel-polyfill');
require('bootstrap-datepicker');
require('bootstrap-select');
require('bootstrap-switch');
require('bootstrap-tagsinput');
require('dropzone');
require('lity');
require('selectize');

/**
 * Bind some bits to the window for usage.
 */

window.apiRequest = new ApiRequest();
window.CandyEvent = new Vue();
window.channels   = [];
window.config     = new Config();
window.Dispatcher = new Dispatcher();
window.languages  = [];
window.List       = require('list.js');
window.locale     = new Locale();
window.moment     = require('moment');

// Include our custom v stuff here, so we know everything is loaded

require('./directives/sortable');
import Vue from 'vue'
import Vuex from 'vuex'
import { VTooltip } from 'v-tooltip'
Vue.use(Vuex);

require('./filters/attributes');
require('./filters/format-date');
require('./filters/translate');


const store = new Vuex.Store({
  state: {
    topTabs: {},
    defaultChannel: {
      handle: 'aqua-spa-supplies'
    }
  },
  mutations: {
    addTab (state, tab) {
      state.topTabs.push(tab);
    },
    addTabs(state, tabs) {
      tabs.forEach(tab => {
        state.topTabs[tab.href] = tab;
      });
    },
    setDefaultChannel(state, channel) {
      state.defaultChannel = channel;
    }
  },
  getters: {
    getTopTabs(state) {
      return state.topTabs;
    },
    getDefaultChannel(state) {
      return state.defaultChannel;
    }
  }
});

let items = store.getters.getTopTabs;

console.log(items);

var defaultChannel = 'aqua-spa-supplies';

config.get('channels').then(response => {
  channels = response.data;
  defaultChannel = _.filter(channels, function(channel) {
    return channel.default;
  });
  store.commit('setDefaultChannel', defaultChannel[0]);
});

config.get('languages').then(response => {
  languages = response.data;
});


Vue.directive('tooltip', VTooltip);


var CandyHelpers = {};

CandyHelpers.install = function (Vue, options) {
  Vue.capitalize = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
};



const app = new Vue({
    el: '#app',
    store,
    data: {
      title: ''
    },
    mounted() {
      CandyEvent.$on('title-changed', event => {
        if (event.prefix) {
          this.title = event.prefix + ' ';
        }
        if (_.isString(event.title)) {
          this.title += event.title;
        } else {
          this.title += this.$options.filters.attribute(event.title, 'name');
        }
      });
    }
});

Vue.use(CandyHelpers);

window.axios.interceptors.response.use((response) => { // intercept the global error
  return response
}, function (error) {
  if (error.response.status === 401) {
      window.location.href = '/login';
      return;
  }
  // Do something with response error
  return Promise.reject(error)
});

/* Misc crap - need to remove!!! */

require('./misc.js');