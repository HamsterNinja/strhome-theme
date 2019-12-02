import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const set = key => (state, val) => {
    state[key] = val
}


const store = new Vuex.Store({
    state: {
        catalogCategory: SITEDATA.category_slug,
        pageNum: 1,
        showLoader: false,
        loadingProducts: false,
        product: {},
        products: [],
        category_count: '',
        category_count_page: 9,
    },
    getters: {
        getProductCount: state => state.productCount,
        allProducts: (state, getters) => {
            return state.products
        },
    },
    mutations: {
        ALL_PRODUCTS(state) {
            state.showLoader = true
        },
        ALL_PRODUCTS_SUCCESS(state, payload) {
            state.showLoader = false;
            state.products = payload;
            let topItems = document.querySelector(".filter-title-bar");
            if (topItems){
                topItems.scrollIntoView({block: "start", behavior: "smooth"});
            }
        },
        updateCategoryCount(state, payload){
            state.category_count = payload
        },
        updateCategoryCountPage(state, payload){
            state.category_count_page = payload
        },
        updateCategoryCount: set('category_count'),
        updateCategoryCountPage: set('category_count_page'),
        updatePageNum: set('pageNum'),
        updateLoadingProducts: set('loadingProducts'),
        updateProductCount: set('productCount'),
        updateCatalogCategory: set('catalogCategory'),
    },
    actions: {
        async allProducts ({commit}, vm ) {
            try {
            commit('ALL_PRODUCTS');
            let catalogCategory = this.state.catalogCategory !=='null' ? this.state.catalogCategory: '';
            let catalogPaged = this.state.pageNum ?  this.state.pageNum : SITEDATA.paged;
            let searchString = this.state.searchString;
            let searchData = `product-cat=${catalogCategory}`;
            let responseProducts = "";
            

            function isAttibute(element) {
                //TODO: получать атрибуты из wp
                let AttibuteParametrNames = [
                    'colors',
                    'sizes',
                ];
                if (AttibuteParametrNames.includes(element)) {
                    return element;
                }
                else{
                    return false;
                }
            }

            let pathArray = window.location.pathname.split('/');
            let pathArrayFiltered = pathArray.filter((el) => {return el != ''});
            let AttibuteParametr = pathArrayFiltered[pathArrayFiltered.findIndex(isAttibute)];
            let AttibuteValue = pathArrayFiltered[pathArrayFiltered.findIndex(isAttibute) + 1];
            
            commit('updateLoadingProducts', true);
            if(SITEDATA.category_slug || (SITEDATA.is_shop === 'true')){
                console.log('cat branch');
                responseProducts = await fetch(`${SITEDATA.url}/wp-json/amadreh/v1/get-products/?${searchData}&paged=${catalogPaged}`);
            }
            else if (SITEDATA.category_slug && !(SITEDATA.is_filter === 'true')) {
                console.log('filter branch');
                responseProducts = await fetch(`${SITEDATA.url}/wp-json/amadreh/v1/get-products/?${searchData}&product-cat=${SITEDATA.category_slug}&paged=${catalogPaged}`);
            }
            else if(AttibuteParametr && AttibuteValue){
                console.log('AttibuteParametr branch');
                responseProducts = await fetch(`${SITEDATA.url}/wp-json/amadreh/v1/get-products/?${AttibuteParametr}=${AttibuteValue}&paged=${catalogPaged}&order_by=${catalogItemsOrderBy}`);
            }
            
            if(responseProducts){
                const dataProducts = await responseProducts.json();
                commit('ALL_PRODUCTS_SUCCESS', dataProducts.data.posts);
                commit('updateCategoryCount', dataProducts.data.found_posts);
                commit('updateCategoryCountPage', Math.ceil(dataProducts.data.found_posts / 9));
            }
            commit('updateLoadingProducts', false);
            } catch (error) {
                console.error(error);
            }
        },
    },
});

export default store;