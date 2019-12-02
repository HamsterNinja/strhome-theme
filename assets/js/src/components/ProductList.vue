<template>
    <div class="our-works-container">
        <transition name="fade">
            <div class="loader-overlay" v-if="loadingProducts">
                <div class="loader"></div>
            </div>
        </transition>
        <transition-group name="products" tag="section" class="our-works-inner" :style="[products && products.length == 0 ? {marginBottom: '0px'} : {marginBottom: '60px'}]">
            <template v-if="products && products.length > 0">
                <product-item v-for="(product, index) in products" :product="product" :class="classItem" :key="index"></product-item>
            </template>
        </transition-group>
        <template v-if="products && products.length == 0 && !loadingProducts">
            <div class="not-content">Категория пуста</div>
        </template>
    </div>
</template>

<script>
export default {
    name: "product-list",
    created() {
        this.$store.dispatch("allProducts");
    },
    data() {
        return {
            template_url: SITEDATA.themepath
        };
    },
    computed: {
        products() {
            return this.$store.getters.allProducts;
        },
        loadingProducts () {
            return this.$store.state.loadingProducts
        }
    },
};
</script>