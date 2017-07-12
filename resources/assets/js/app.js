
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('babel-core/register');
require('babel-polyfill');



require('./classes/Errors');
require('./classes/Form');


Array.prototype.unique = function() {
    var a = this.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

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
Vue.component('candy-modal', require('./components/elements/Modal.vue'));

/**
 * Form Components
 */
Vue.component('candy-field', require('./components/elements/forms/Field.vue'));
Vue.component('candy-input', require('./components/elements/forms/Input.vue'));
Vue.component('candy-taggable', require('./components/elements/forms/Taggable.vue'));
Vue.component('candy-select', require('./components/elements/forms/Select.vue'));
Vue.component('candy-textarea', require('./components/elements/forms/Textarea.vue'));
Vue.component('candy-time', require('./components/elements/forms/Time.vue'));
Vue.component('candy-date', require('./components/elements/forms/Date.vue'));

Vue.component('products-table', require('./components/catalogue-manager/products/ProductsTable.vue'));
Vue.component('candy-product-edit', require('./components/catalogue-manager/products/ProductEdit.vue'));
Vue.component('candy-product-details', require('./components/catalogue-manager/products/edit/ProductDetails.vue'));
Vue.component('candy-product-attributes', require('./components/catalogue-manager/products/edit/details/ProductAttributes.vue'));
Vue.component('candy-product-variants', require('./components/catalogue-manager/products/edit/ProductVariants.vue'));

/**
 * Media
 */
Vue.component('candy-media', require('./components/catalogue-manager/products/edit/media/Media.vue'));

/**
 * Avalability & Pricing
 */
Vue.component('candy-pricing-variants', require('./components/catalogue-manager/products/edit/availability-pricing/PricingVariants.vue'));
Vue.component('candy-inventory', require('./components/catalogue-manager/products/edit/availability-pricing/Inventory.vue'));
Vue.component('candy-shipping', require('./components/catalogue-manager/products/edit/availability-pricing/Shipping.vue'));
Vue.component('candy-channels', require('./components/catalogue-manager/products/edit/availability-pricing/Channels.vue'));
Vue.component('candy-customer-groups', require('./components/catalogue-manager/products/edit/availability-pricing/CustomerGroups.vue'));
Vue.component('candy-discounts', require('./components/catalogue-manager/products/edit/availability-pricing/Discounts.vue'));
Vue.component('candy-avalability-pricing-modals', require('./components/catalogue-manager/products/edit/availability-pricing/Modals.vue'));

/**
 * if Variants
 */
Vue.component('candy-variants', require('./components/catalogue-manager/products/edit/availability-pricing/Variants.vue'));
Vue.component('candy-edit-variants', require('./components/catalogue-manager/products/edit/variants/EditVariants.vue'));

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

window.Event = new Vue();

var ApiRequest = require('./classes/ApiRequest');
window.apiRequest = new ApiRequest();


var CandyHelpers = {};

CandyHelpers.install = function (Vue, options) {
  Vue.capitalize = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
}

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