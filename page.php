<?php

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

$templates = [ 'page-' . $timber_post->post_name . '.twig', 'page.twig' ];

$parent_page_id = $post->post_parent;
$page_name = $post->post_name;
$parent_page = get_post($parent_page_id);
$parent_page_name = $parent_page->post_name;


$parent_page_names = ["building"];
if (in_array($parent_page_name, $parent_page_names)) {
    array_unshift( $templates, 'page-building.twig' );
}

Timber::render( $templates, $context );