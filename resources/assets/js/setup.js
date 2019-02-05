import Hub from './candyhub';

var ApiRequest  = require('./classes/ApiRequest');

window._ = require('lodash');
window.apiRequest = new ApiRequest();
window.CandyHub = Hub;
