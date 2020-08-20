import 'babel-polyfill';
import Vue from 'vue';

import Vuex from 'vuex';
Vue.use(Vuex);
import store from './store';

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import СontactForm from './components/СontactForm.vue';
Vue.component('contact-form', СontactForm);

import ProductList from './components/ProductList.vue';
Vue.component('product-list', ProductList);

import productItem from './components/ProductItem.vue';
Vue.component('product-item', productItem);

let elVue = "#app";
let elVueQuery = document.querySelector(elVue);

if (elVueQuery) {
    const app = new Vue({
        el: elVue,
        store,
        delimiters: ["((", "))"],
        components: {},
        data: {
            errors: [],
            template_url: SITEDATA.themepath,
        },
        methods: {
            showModal: (modalName) => {
                const currentModal = document.querySelector(`.${modalName}`);
                const overlay = document.querySelector('.overlay');
                if (currentModal) {
                    currentModal.classList.add('modal--show');
                    overlay.classList.add('overlay--show');
                }
            },

            closeModal: () => {
                const overlay = document.querySelector('.overlay');
                const modals = document.querySelectorAll('.modal-window');
                modals.forEach(modal => {
                    modal.classList.remove('modal--show');
                    overlay.classList.remove('overlay--show');
                });
            },
        },
    })
};

document.addEventListener('DOMContentLoaded', () => {
    $('.main-slick').slick({
        slidesToShow: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        arrows: true,
        dots: true,
        swipeToSlide: true,
    });
    
    $('.projects-slick').slick({
        slidesToShow: 1,
        centerMode: true,
        centerPadding: '500px',
        autoplay: false,
        autoplaySpeed: 3000,
        arrows: true,
        dots: false,
        swipeToSlide: true,
    });
    
    
    $('.product-slick-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.product-slick-nav'
    });
    
    $('.product-slick-nav').slick({
        slidesToShow: 10,
        slidesToScroll: 1,
        asNavFor: '.product-slick-for',
        focusOnSelect: true,
        arrows: false,
    });

});

$('.btn-hamburger').click(function() {    
    $('.mobile-menu').addClass('active');
});
$('.btn-hamburger.active').click(function() {    
    $('.mobile-menu').removeClass('active');
});

$(".mobile-menu .menu-item-has-children>a").after('<div class="menu-toggle"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 200 200"><path d="M193.177 46.233l8.28 8.28-100.723 100.728L0 54.495l8.28-8.279 92.46 92.46 92.437-92.443z" fill="#ffffff"/></svg></div>');
     $('.menu-toggle').click(function() {
        $(this).parent().find('.sub-menu').slideToggle();
        $(this).toggleClass('active');
    });
$('.tab-button').click(function(event) {
  event.preventDefault();
  $('.tab-button').removeClass('active');
  $(this).addClass('active');
  
  var id=$(this).attr('data-id');
  if (id){
    $('.tab-content-inner:visible').fadeOut(0);
    $('.tab-content').find('#'+id).fadeIn('slow');
    }
});

