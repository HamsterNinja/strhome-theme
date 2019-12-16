<?php
//Получение продуктов
function getProducts(WP_REST_Request $request) {
    function roundArray($n){
        return round($n, 1);
    };
    if(isset ($_GET)){
        $current_search = $_GET['search'];
        $current_product_cat = $_GET['product-cat'];
        $current_items_order_by = $_GET['order_by'];
        $current_paged = $_GET['paged'];
        $include = $_GET['include'];
        
        $current_colors = $_GET['colors'] ? explode( ',', $_GET['colors']) : [];   
     
        $args = array(
            'post_status' => 'publish',
            'post_type' => array('product', 'product_variation'),
            'post_type' => 'product',
            'posts_per_page' => 9,  
            's' => $current_search,
            'paged' => ( $current_paged ? $current_paged : 1 ),
        );
        if($include){
            $args['post__in'] = explode( ',', $include);
        }
        $args['tax_query'] =  array('relation' => 'AND');
        $args['meta_query'] =  array('relation' => 'AND');
        if (isset($current_product_cat)  && !(empty($current_product_cat)) && !is_null($current_product_cat) && !($current_product_cat=="null")) {
            $request_params = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $current_product_cat
            );
            array_push($args['tax_query'], $request_params); 
        }
        if (isset($current_colors)  && !(empty($current_colors))) {
            $request_params = array(
                'taxonomy' => 'pa_colors',
                'field' => 'slug',
                'terms' => $current_colors,
            );
            array_push($args['tax_query'], $request_params); 
        }
        
        function trunc($phrase, $max_words) {
            $phrase_array = explode(' ',$phrase);
            if(count($phrase_array) > $max_words && $max_words > 0)
               $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
            return $phrase;
        }
        $result = new WP_Query($args);
        $products = [];
        function price_array($price){
            $del = array('<span class="woocommerce-Price-amount amount">', '<span class="woocommerce-Price-currencySymbol">' ,'</span>','<del>','<ins>', '&#8381;');
            $price = str_replace($del, '', $price);
            $price = str_replace('</del>', '|', $price);
            $price = str_replace('</ins>', '|', $price);
            $price_arr = explode('|', $price);
            $price_arr = array_filter($price_arr);
            return $price_arr;
        }
        foreach ($result->posts as $post) {
            $productInstance = new WC_Product($post->ID);
            $product = (object)[];
            $product->id = $post->ID;
            $product->slug = $post->post_name;
            $product->name = $post->post_title;
            $product->permalink = get_permalink($post->ID);
            $product->regular_price = $productInstance->get_regular_price();
            $product->sale_price = $productInstance->get_sale_price();
            $product->excerpt = trunc(get_the_excerpt( $post ), 10);
            $product->images = [];   
            //product_tag
            $terms_product_tag = get_the_terms( $post->ID, 'product_tag' );
            $term_array_product_tag = array();
            if ( ! empty( $terms_product_tag ) && ! is_wp_error( $terms_product_tag ) ){
                foreach ( $terms_product_tag as $term ) {
                    $term_array_product_tag[] = $term->name;
                }
            }
            $product->product_tags = $term_array_product_tag;
            $sizes_attributes = $productInstance->get_attribute( 'sizes' );
            $product->sizes_attributes = $sizes_attributes;
            $price_html = $productInstance->get_price_html();
            $product->price_html = price_array($price_html);
            global $_wp_additional_image_sizes;
            foreach ($_wp_additional_image_sizes as $size => $value) {
                $image_info = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);
                $product->images[0][$size] = $image_info[0];
            }
            $product->acf = get_fields($post->ID);
            array_push($products, $product);
        }
        $response = (object)[];
        $response->posts = $products;
        $response->max_num_pages = $result->max_num_pages;
        $response->found_posts = $result->found_posts;
        $response->post_count = $result->post_count;
        $response->current_post = $result->current_post;
        wp_send_json_success( $response , 200 );
    }
    wp_send_json_error('Ошибка при получение значений продуктов');
}
add_action( 'rest_api_init', function () {
    register_rest_route( 'amadreh/v1/', '/get-products', array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => 'getProducts',
      ) );
});
