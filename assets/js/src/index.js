import 'babel-polyfill';
import Vue from 'vue';

import Vuex from 'vuex';
Vue.use(Vuex);
import store from './store';

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import СontactForm from './components/СontactForm.vue';
Vue.component('contact-form', СontactForm);

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
        responsive: [{
                breakpoint: 1600,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '350px',
                }
            },
            {
                breakpoint: 1280,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '200px',
                    slidesToShow: 1
                }
            }
        ]
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