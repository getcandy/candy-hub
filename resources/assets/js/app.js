
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

config.get('channels').then(response => {
  channels = response.data;
});
config.get('languages').then(response => {
  languages = response.data;
});

// Include our custom v stuff here, so we know everything is loaded

require('./directives/sortable');
require('./filters/attributes');
require('./filters/format-date');
require('./filters/translate');

var CandyHelpers = {};

CandyHelpers.install = function (Vue, options) {
  Vue.capitalize = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
};

const app = new Vue({
    el: '#app',
    data: {
    },
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