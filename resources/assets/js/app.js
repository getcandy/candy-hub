
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('babel-core/register');
require('lity');
require('babel-polyfill');

require('./classes/Errors');
require('./classes/Form');

//window.Datepicker = require('bootstrap-datepicker');
require('bootstrap-datepicker');
require('bootstrap-select');
require('bootstrap-switch');
require('bootstrap-tagsinput');
require('dropzone');
require('selectize');

window.List = require('list.js');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/**
 * Generic components
 */

Vue.component('candy-tabs', require('./components/elements/tabs/CandyTabs.vue'));
Vue.component('candy-tab', require('./components/elements/tabs/CandyTab.vue'));
Vue.component('candy-button', require('./components/elements/Button.vue'));
Vue.component('candy-notification', require('./components/elements/NotificationBar.vue'));
Vue.component('candy-alert', require('./components/elements/AlertPanel.vue'));
Vue.component('candy-modal', require('./components/elements/Modal.vue'));

/**
 * Form Components
 */
Vue.component('candy-checkbox', require('./components/elements/forms/Checkbox.vue'));
Vue.component('candy-input', require('./components/elements/forms/Input.vue'));
Vue.component('candy-radio', require('./components/elements/forms/Radio.vue'));
Vue.component('candy-select', require('./components/elements/forms/Select.vue'));
Vue.component('candy-taggable', require('./components/elements/forms/Taggable.vue'));
Vue.component('candy-textarea', require('./components/elements/forms/Textarea.vue'));
Vue.component('candy-toggle', require('./components/elements/forms/Toggle.vue'));

/**
 * Page Specific
 */
/* Products */
Vue.component('products-table', require('./components/catalogue-manager/products/ProductsTable.vue'));
Vue.component('candy-product-edit', require('./components/catalogue-manager/products/ProductEdit.vue'));
Vue.component('candy-product-details', require('./components/catalogue-manager/products/edit/ProductDetails.vue'));
Vue.component('candy-product-attributes', require('./components/catalogue-manager/products/edit/details/ProductAttributes.vue'));
Vue.component('candy-product-variants', require('./components/catalogue-manager/products/edit/ProductVariants.vue'));

/* Collections */
Vue.component('collections-table', require('./components/catalogue-manager/collections/CollectionsTable.vue'));

/* Categories */
Vue.component('categories-list', require('./components/catalogue-manager/categories/CategoriesList.vue'));
Vue.component('candy-category', require('./components/catalogue-manager/categories/category/category.vue'));

/**
 * Table
 */
Vue.component('candy-table-paginate', require('./components/elements/tables/TablePaginate.vue'));

/**
 * Media
 */
Vue.component('candy-media', require('./components/catalogue-manager/products/edit/media/Media.vue'));

/**
 * Avalability & Pricing
 */

Vue.component('candy-discounts', require('./components/catalogue-manager/products/edit/availability-pricing/Discounts.vue'));
Vue.component('candy-product-availability', require('./components/catalogue-manager/products/edit/ProductAvailability.vue'));

/**
 * if Variants
 */
Vue.component('candy-variants', require('./components/catalogue-manager/products/edit/availability-pricing/Variants.vue'));
Vue.component('candy-create-variant', require('./components/catalogue-manager/products/edit/availability-pricing/CreateVariant.vue'));
Vue.component('candy-edit-options', require('./components/catalogue-manager/products/edit/availability-pricing/EditOptions.vue'));
/**
 * Associations
 */
Vue.component('candy-categories', require('./components/catalogue-manager/products/edit/associations/Categories.vue'));
Vue.component('candy-collections', require('./components/catalogue-manager/products/edit/associations/Collections.vue'));
Vue.component('candy-products', require('./components/catalogue-manager/products/edit/associations/Products.vue'));
Vue.component('candy-association-modals', require('./components/catalogue-manager/products/edit/associations/Modals.vue'));

/**
 * Display
 */
Vue.component('candy-display', require('./components/catalogue-manager/products/edit/display/Display.vue'));

/**
 * URLS
 */
Vue.component('candy-locale-urls', require('./components/catalogue-manager/products/edit/urls/LocaleURLs.vue'));
Vue.component('candy-redirects', require('./components/catalogue-manager/products/edit/urls/Redirects.vue'));
Vue.component('candy-url-modals', require('./components/catalogue-manager/products/edit/urls/Modals.vue'));

/**
 * Directives
 */

import Sortable from 'sortablejs';

Vue.directive('sortable', {
  inserted: function (el, binding) {
    var sortable = new Sortable(el, binding.value || {});
  }
});

window.CandyEvent = new Vue();

var ApiRequest = require('./classes/ApiRequest');
window.apiRequest = new ApiRequest();

var Locale = require('./classes/Locale');
window.locale = new Locale();

var CandyHelpers = {};

CandyHelpers.install = function (Vue, options) {
  Vue.capitalize = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
}

window.moment = require('moment');

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format('MM/DD/YYYY hh:mm')
  }
});

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

// Clickable Table Row
$(".clickable .link").click(function() {
    window.location = $(this).data("href");
});

// Adding /Removing table row for product options

// Navigation Purple Overlay
$('.top-level').hover (
   function(){ $('.main-purple-overlay').addClass('active'); $('.side-purple-overlay').addClass('active'); },
   function(){ $('.main-purple-overlay').removeClass('active'); $('.side-purple-overlay').removeClass('active'); }
);

// Filter Pop Over
$('.btn-pop-over').click(function() {
    $('.pop-over').toggleClass('active');
});

// Product Menu
$('.product-menu').click(function() {
    $(this).toggleClass('active');
});
$('.bulk-actions').css({
    'width': ($('.product-table').width() + 'px')
});

// Tabs
var hash = document.location.hash;
var prefix = "tab_";
if (hash) {
    $('.nav-tabs a[href="'+hash.replace(prefix,"")+'"]').tab('show');
}

$('.nav-tabs a').on('shown', function (e) {
    window.location.hash = e.target.hash.replace("#", "#" + prefix);
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("href");
  $('.bulk-actions').css({
    'width': ($('.product-table').width() + 'px')
  });
  // Variant image height same as width
  var variantImage = $('.variant-option-img').width();
  $('.variant-option-img').css({'height':variantImage+'px'});

  // Media dropzone height same as width
  var dropzone = $('.dropzone').width();
  $('.dropzone').css({'height':dropzone+'px'});
});

// Tooltips
$("[data-toggle='tooltip']").tooltip();

// Tooltips
$('[data-toggle="popover"]').popover();

// Date Picker

// Switch
$(".toggle input").bootstrapSwitch();

// Category, Collection, Product List Search.

var options = {
  valueNames: [ 'name' ]
};

var optionsTwo = {
  valueNames: [ 'name', 'sku', 'category', 'collection' ]
};

var categoryList = new List('categoryList', options);
var collectionList = new List('collectionList', options);
var productList = new List('productList', optionsTwo);

// Category, Collection Associations, hightlight

$('.association-table input:checkbox').change(function(){
    if($(this).is(":checked")) {
        $(this).parents('tr').addClass("selected");
    } else {
        $(this).parents('tr').removeClass("selected");
    }
});