<?php
  if (class_exists('Timber')) {
    Timber::$cache = false;
  }

  function filter_ptags_on_images($content){
//функция preg replace, которая убивает тег p
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'filter_ptags_on_images');

//Скрытие версии wp
  add_filter('the_generator', '__return_empty_string');

  add_image_size('blog_image', 438, 257, true);
  add_image_size('catalog_image', 360, 370, true);
  add_image_size('catalog_image_x2', 720, 405, true);

  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');
  function disable_wp_emojis_in_tinymce($plugins)
  {
    if (is_array($plugins)) {
      return array_diff($plugins, array('wpemoji'));
    } else {
      return array();
    }
  }

  function true_remove_default_widget()
  {
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Nav_Menu_Widget');
  }

  add_action('widgets_init', 'true_remove_default_widget', 20);
  add_theme_support('post-thumbnails');

  register_nav_menus(array(
    'menu_header' => 'Верхнее меню',
    'menu_footer_projects' => 'Нижние меню проекты',
    'menu_footer_about' => 'Нижние меню о нас',
  ));

  function add_async_forscript($url)
  {
    if (strpos($url, '#asyncload') === false)
      return $url;
    else if (is_admin())
      return str_replace('#asyncload', '', $url);
    else
      return str_replace('#asyncload', '', $url) . "' defer='defer";
  }

  add_filter('clean_url', 'add_async_forscript', 11, 1);
  function time_enqueuer($my_handle, $relpath, $type = 'script', $async = 'false', $media = "all", $my_deps = array())
  {
    if ($async == 'true') {
      $uri = get_theme_file_uri($relpath . '#asyncload');
    } else {
      $uri = get_theme_file_uri($relpath);
    }
    $vsn = filemtime(get_theme_file_path($relpath));
    if ($type == 'script') wp_enqueue_script($my_handle, $uri, $my_deps, $vsn);
    else if ($type == 'style') wp_enqueue_style($my_handle, $uri, $my_deps, $vsn, $media);
  }

  add_action('wp_footer', 'add_scripts');
  function add_scripts()
  {
    time_enqueuer('jquerylatest', '/assets/js/vendors/jquery-3.4.1.min.js', 'script', true);
    time_enqueuer('slick', '/assets/js/vendors/slick.js', 'script', true);
    // time_enqueuer('lazyload', '/assets/js/vendors/lazyload.min.js', 'script', true);
    time_enqueuer('app', '/assets/js/main.bundle.js', 'script', true);
    time_enqueuer('custom', '/assets/js/custom.js', 'script', true);

    $queried_object = get_queried_object();
    if ($queried_object) {
      $term_id = $queried_object->term_id;
      $term = get_term($term_id, 'product_cat');
      $category_slug = $term->slug;
      $current_brand_term = get_term($term_id, 'brand_product');
      $current_brand = $current_brand_term->slug;
    }
    if ($_GET && $category_slug == null) {
      if ($_GET['product-cat']) {
        $category_slug = $_GET['product-cat'];
      }
    }

    wp_localize_script('app', 'SITEDATA', array(
      'url' => get_site_url(),
      'themepath' => get_template_directory_uri(),
      'ajax_url' => admin_url('admin-ajax.php'),
      'is_home' => is_home() ? 'true' : 'false',
      'is_product' => is_product() ? 'true' : 'false',
      'is_filter' => is_page('filter') ? 'true' : 'false',
      'is_cart' => is_cart() ? 'true' : 'false',
      'is_search' => is_search() ? 'true' : 'false',
      'search_query' => get_search_query() ? get_search_query() : '',
      'product_id' => get_the_ID(),
      'current_user_id' => get_current_user_id(),
      'category_slug' => $category_slug,
    ));
  }

//wp-embed.min.js remove
  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('wp_head', 'wp_oembed_add_host_js');

//remove jquery-migrate
  function dequeue_jquery_migrate($scripts)
  {
    if (!is_admin() && !empty($scripts->registered['jquery'])) {
      $jquery_dependencies = $scripts->registered['jquery']->deps;
      $scripts->registered['jquery']->deps = array_diff($jquery_dependencies, array('jquery-migrate'));
    }
  }

  add_action('wp_default_scripts', 'dequeue_jquery_migrate');

  function add_styles()
  {
    if (is_admin()) return false;
    time_enqueuer('main', '/assets/css/main.css', 'style', false, 'all');
  }

  add_action('wp_print_styles', 'add_styles');

  Timber::$dirname = array('templates', 'views');

  class StarterSite extends TimberSite
  {
    function __construct()
    {
      add_theme_support('post-formats');
      add_theme_support('post-thumbnails');
      add_theme_support('woocommerce');
      add_theme_support('menus');
      add_filter('timber_context', array($this, 'add_to_context'));
      add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
      parent::__construct();
    }


    function add_to_context($context)
    {
      $context['gallery'] = get_field('галерея');
      $context['chars'] = get_field('характеристики');
      $context['complects'] = get_field('комплектации_и_цены');
      $context['planirovka'] = get_field('планировка');
      $context['area'] = get_field('общая_площадь');
      $context['top_title'] = get_field('заголовок_сверху');
      $context['bottom_title'] = get_field('заголовок_снизу');
      $context['top_text'] = get_field('текст_сверху');
      $context['bottom_text'] = get_field('текст_снизу');
      $context['min_price'] = get_field('минимальная_стоимость');
      $context['menu_header'] = new TimberMenu('menu_header');
      $context['menu_footer_projects'] = new TimberMenu('menu_footer_projects');
      $context['menu_footer_about'] = new TimberMenu('menu_footer_about');
      $context['services_text'] = get_field('services_text');
      $context['city'] = get_field('в_городе');
      $context['site'] = $this;

      $context['phone'] = TimberHelper::transient('phone', function () {
        return get_field('phone', 'options');
      }, 600);
      $context['mail'] = TimberHelper::transient('mail', function () {
        return get_field('mail', 'options');
      }, 600);

      $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'post_parent' => 0,
        'orderby' => 'rand'
      );
      $context['random_prod'] = Timber::get_posts($args);

      $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'post_parent' => 0,
      );
      $context['our_prod'] = Timber::get_posts($args);

      return $context;
    }
  }

  new StarterSite();

//Disable gutenberg style in Front
  function wps_deregister_styles()
  {
    wp_dequeue_style('wp-block-library');
  }

  add_action('wp_print_styles', 'wps_deregister_styles', 100);

//remove type js and css for validator
  add_action('wp_loaded', 'prefix_output_buffer_start');
  function prefix_output_buffer_start()
  {
    ob_start("prefix_output_callback");
  }

  add_action('shutdown', 'prefix_output_buffer_end');
  function prefix_output_buffer_end()
  {
    ob_end_flush();
  }

  function prefix_output_callback($buffer)
  {
    return preg_replace("%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $buffer);
  }

  include_once(get_template_directory() . '/include/acf-fields.php');
  include_once(get_template_directory() . '/include/rest-api.php');
  include_once(get_template_directory() . '/include/theme-settings.php');