<template>
    <a :href="product.permalink" class="our-works-item" style="background: linear-gradient(360deg, #000000 3.07%, rgba(89, 89, 89, 0) 43.4%),url(/assets/images/work.jpg)">
		<div class="our-works-item-content">
			<div class="our-works-item-name">{{ product.name }}</div>
			<div class="our-works-item-info">
				<div class="our-works-item-size">212,4 М2</div>
				<div class="our-works-item-price">45 000 P</div>
			</div>
		</div>
	</a>
</template>

<script>
export default {
    name: "product-item",
    props: {
        product: Object
    },
    mounted() {},
    data() {
        return {
            template_url: SITEDATA.themepath,
            is_home: SITEDATA.is_home == "true",
        };
    },
    computed: {},
    methods: {}
};
</script>