<? 

if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Основные настройки',
        'menu_title'    => 'Основные настройки',
        'menu_slug'     => 'options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

add_action( 'init', 'create_my_post_types' );
function create_my_post_types() {
    register_post_type(
    'city',
    array('labels' => array( 'name' => __( 'Города' ),
    'singular_name' => __( 'Город' ) ),
    'supports'      => array( 'title', 'thumbnail'),
    'public' => true, ) );
}