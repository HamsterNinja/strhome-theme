<?php

if ( ! class_exists( 'Timber' ) ){
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
    return;
}
$context = Timber::get_context();
$templates = array( 'archive-product.twig' );


// WooCommerce Notices
$context['wc_notices'] = wc_get_notices();
wc_clear_notices();

if ( is_singular( 'product' ) ) {
    $context['post'] = new TimberPost();
    $product = wc_get_product( $context['post']->ID );
    $context['product'] = $product;

    //images product
    $attachment_ids = $product->get_gallery_image_ids();
    $context['attachment_ids'] = $attachment_ids;
    $context['regular_price'] = $product->get_regular_price();
    $context['sale_price'] = $product->get_sale_price();
    $context['sku'] = $product->get_sku();

    Timber::render( 'single-product.twig', $context );
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;
    $context['title'] = 'Магазин';

    if ( is_product_category() || is_shop() ) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $term = get_term( $term_id, 'product_cat' );
        $context['category'] = $term;
        $context['category_slug'] = $term->slug;
        $context['category_count'] = $term->count;
        $context['title'] = single_term_title( '', false );

        $subcategories = get_terms( array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term_id, 
            'exclude' => 15
        ) );
        $context['subcategories'] = $subcategories;

        array_unshift( $templates, 'taxonomy-product_cat-' . $term->slug . '.twig' );
    }
    
    Timber::render( $templates, $context );
}