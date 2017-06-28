
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('bootstrap-datepicker');
require('bootstrap-select');
require('bootstrap-switch');
require('bootstrap-tagsinput');
require('dropzone');
require('list.js');

require("babel-core/register");
require("babel-polyfill");

require('./classes/Errors');
require('./classes/Form');


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

/**
 * Form Components
 */
Vue.component('candy-field', require('./components/elements/forms/Field.vue'));
Vue.component('candy-input', require('./components/elements/forms/Input.vue'));

Vue.component('products-table', require('./components/catalogue-manager/products/ProductsTable.vue'));
Vue.component('candy-product-edit', require('./components/catalogue-manager/products/ProductEdit.vue'));
Vue.component('candy-product-details', require('./components/catalogue-manager/products/edit/ProductDetails.vue'));

window.Event = new Vue();

var Dispatcher = require('./classes/Dispatcher');
window.dispatcher = new Dispatcher();

var ApiRequest = require('./classes/ApiRequest');
window.apiRequest = new ApiRequest();

const app = new Vue({
    el: '#app',
    data: {
    }
});


window.axios.interceptors.response.use((response) => { // intercept the global error
    return response
  }, function (error) {
    if (error.response.status === 401) {
        window.location.href = '/login';
        return;
    }
    // Do something with response error
    return Promise.reject(error)
  })




/* Misc crap - need to remove!!! */

// Clickable Table Row
$(".clickable .link").click(function() {
    window.location = $(this).data("href");
});

// Adding /Removing table row for product options

$('.add-variant-option').bind('click', function(){
  $('<tr><td width="30%"><input type="text" class="form-control"></td><td width="60%"><input type="text" class="form-control" data-role="tagsinput"></td><td align="right"><button class="btn btn-sm btn-default btn-action delete-row"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>').insertBefore($(this).closest('tr'));
  $('.delete-row').bind('click', function(){
    $(this).closest('tr').remove();
  });
});

$('.edit_add-variant-option').bind('click', function(){
  $('<tr><td><input type="text" class="form-control" value="Option Name"></td><td width="60%"><input type="text" class="form-control" data-role="tagsinput" value="Need to edit jQuery to fire tagsinput script on additional line"></td><td align="right"><button class="btn btn-sm btn-default btn-action delete-row"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>').insertBefore($(this).closest('tr'));
  $('.delete-row').bind('click', function(){
    $(this).closest('tr').remove();
  });
});

$('.delete-row').bind('click', function(){
  $(this).closest('tr').remove();
});

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

// Bulk Options
$('.bulk-options').click(function() {
    $(this).toggleClass('active');
});

$('.bulk-actions').css({
    'width': ($('.product-table').width() + 'px')
 });

$('.bulk-options').on('click', function(){
   var checkbox = $(this).children('input[type="checkbox"]');
   checkbox.prop('checked', !checkbox.prop('checked'));
});

$('.product-table input:checkbox').change(function(){
    if($(this).is(":checked")) {
        $('.bulk-options').addClass("active");
        $(this).parents('tr').addClass("selected");
    } else {
        $('.bulk-options').removeClass("active");
        $(this).parents('tr').removeClass("selected");
    }
});

$(".select-all").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
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
  var target = $(e.target).attr("href")
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
$('[data-toggle="popover"]').popover()

// Date Picker
$('.date').datepicker({
    format: 'dd/mm/yyyy'
});

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