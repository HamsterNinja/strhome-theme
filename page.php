<?php

  $context = Timber::context();

  $timber_post = new Timber\Post();
  $context['post'] = $timber_post;

  $templates = ['page-' . $timber_post->post_name . '.twig', 'page.twig'];

  $parent_page_id = $post->post_parent;
  $page_name = $post->post_name;
  $parent_page = get_post($parent_page_id);
  $parent_page_name = $parent_page->post_name;


  $parent_page_names = ["building"];
  if (in_array($parent_page_name, $parent_page_names)) {
    array_unshift($templates, 'page-building.twig');
  }

// Фундамент
  $child_args = array(
    'post_parent' => $post->ID,
    'post_type' => 'page',
    'post_status' => 'publish'
  );

  $children = get_children($child_args);
  $context['foundation'] = $children;

// Услуги
  $serv_args = array(
    'post_parent' => $post->ID,
    'post_type' => 'page',
    'post_status' => 'publish'
  );
  $childserv = get_children($serv_args);
  $context['services'] = $childserv;
  // Города
  $args = array(
    'posts_per_page' => '-1',
    'post_type'=>'city',
    'orderby'=>'title',
    'order' => 'ASC'
  );
  $context['cities'] = Timber::get_posts( $args );
  Timber::render($templates, $context);
  //  Timber::render(array['page-other-services.twig'], $context);


