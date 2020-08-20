<?php

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$context['foo']   = 'bar';
$templates        = array( 'index.twig' );

if ( is_home() ) {

	$args_news = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
	);            
	$context['news_posts'] = Timber::get_posts( $args_news );

	$args_popular_projects = array(
		'post_type' => 'product',
		'posts_per_page' => 10,
	);            
	$context['popular_projects'] = Timber::get_posts( $args_popular_projects );

	$banners = get_field('banners', 'options');
	$context['banners'] = $banners;

	$product_categories = get_terms( array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false,
		'parent' => 25,
	) );
	$context['product_categories'] = $product_categories;
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}
Timber::render( $templates, $context );