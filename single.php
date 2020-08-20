<?php

$context         = Timber::context();
$timber_post     = Timber::query_post();
$context['post'] = $timber_post;

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


if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig' ), $context );
}