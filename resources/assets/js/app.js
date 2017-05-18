
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


require('./classes/Errors');
require('./classes/Form');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('products-table', require('./components/catalogue-manager/products/ProductsTable.vue'));

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

// Tooltips
$("[data-toggle='tooltip']").tooltip();