<?php
/**
 * Plugin Name: Product generation for WooCommerce
 * Plugin URI:
 * Description: Generate products from images and more
 * Author: Alex Zemyansky
 * Author URI:
 * Version: 1.0
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     woo-products-from-images
 * @author      Alex Zemyansky
 * @Category    Plugin
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

function plugin_main_page()
{
    include('admin-screen.php');
}

function plugin_menu()
{
    add_submenu_page('woocommerce', 'Генерация товаров', 'Генерация товаров', 'manage_woocommerce', 'woo_products_from_images', 'plugin_main_page');
}
add_action('admin_menu', 'plugin_menu');

function plugin_scripts($hook)
{
    //echo $hook;
    if( 'woocommerce_page_woo_products_from_images' != $hook ) //Подключаем скрипты только на странице плагина
        return;
    wp_enqueue_style('woo-products-from-img-style', plugins_url('css/style.css', __FILE__), '', '1.0.0', 'screen');
    wp_enqueue_style('woo-products-from-img-loader', plugins_url('css/loader.css', __FILE__), '', '1.0.0', 'screen');
    wp_enqueue_style('woo-products-from-img-modal', plugins_url('css/bootstrap.css', __FILE__), '', '1.0.0', 'screen');

    wp_enqueue_script( 'woo-products-from-img-bootstrap-script', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'), '1.0.0' );
    wp_enqueue_script( 'woo-products-from-img-script', plugins_url('js/script.js', __FILE__), array('jquery'), '1.0.0' );

    $generate_products_nonce = wp_create_nonce( 'generate_products_example' );
    wp_localize_script( 'woo-products-from-img-script', 'my_ajax_obj', array( //pass object "my_ajax_obj" to plugin script
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => $generate_products_nonce,
    ) );
}
add_action('admin_enqueue_scripts', 'plugin_scripts');

function generate_products_ajax_handler()
{
    check_ajax_referer('generate_products_example');

    $params = array();
    parse_str($_POST['fields'], $params);

    if (isset($_POST['product_ids']) && !empty($_POST['product_ids']) &&
        isset($params['product_category']) && !empty($params['product_category']) &&
        isset($params['product_variation']) && !empty($params['product_variation']) )
    {
        // REPLACE THE 0 IN FUNCTION PARAM BELOW WITH YOUR REFERENCE PRODUCT ID
        //echo print_r($_POST['product_ids'], true);
        echo wpa_convert_images_to_products( 10 ,
            $_POST['product_ids'], $params['product_category'], $params['product_variation']);
        //echo json_encode($_POST['product_ids']);
    }
    else
        echo 'false';
    wp_die();
}
add_action( 'wp_ajax_generate_products', 'generate_products_ajax_handler' );

function woo_get_all_subcategories()
{
    $taxonomy     = 'product_cat';
    $orderby      = 'name';
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no
    $title        = '';
    $empty        = 0;

    $args = array(
        'taxonomy'     => $taxonomy,
        'orderby'      => $orderby,
        'show_count'   => $show_count,
        'pad_counts'   => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li'     => $title,
        'hide_empty'   => $empty
    );
    $all_categories = get_categories( $args );

    $result = array();
    foreach ($all_categories as $cat) {
        if($cat->category_parent == 0) {
            $category_id = $cat->term_id;
            //echo '<br /><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>';

            $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
            );
            $sub_cats = get_categories( $args2 );
            if($sub_cats) {
                foreach($sub_cats as $sub_category) {
                    array_push($result, array('cat_name' => $sub_category->name, 'cat_id' => $sub_category->term_id));
                    //echo  $sub_category->name ;
                }
            }
        }
    }
    return $result;
}

function woo_get_all_attributes()
{
    global $wpdb;
    if ( get_transient('all_prod_attributes') )
        return get_transient('all_prod_attributes');

    $attribute_vals = $wpdb->get_results( "SELECT b.name, b.slug FROM $wpdb->term_taxonomy a INNER JOIN $wpdb->terms b ON a.term_id=b.term_id ".
                    "WHERE taxonomy='pa_format'");
    set_transient('all_prod_attributes', $attribute_vals, HOUR_IN_SECONDS / 2);
    return $attribute_vals;
}

function wpa_convert_images_to_products($ref_id = 0, $image_ids, $category, $variations ) {

    // following line ensure that present function is run once
    //if (  get_transient('convert_images_to_products_done') ) return;

    //try to remove php limits in execution time an memory
    @set_time_limit (0);
    @ini_set('memory_limit', -1);

    if ( ! post_type_exists( 'product' ) || ! $ref_id )
        wp_die('Reference post id is not valid or product post type is not registered.');

    $reference = new WC_Product_Variable($ref_id);//get_post($ref_id);

    if ( ! $reference )
        wp_die('Given reference post id is not valid.');

//    $ref_thumb = get_post_thumbnail_id( $ref_id );
//    if ( ! is_int($ref_thumb) ) $ref_thumb = null;
//
//    // get reference attributes
//    $product_vars = get_object_vars($reference);
//    unset($product_vars['ID']);
//    unset($product_vars['post_date']);
//    unset($product_vars['post_date_gmt']);
//    unset($product_vars['post_modified']);
//    unset($product_vars['post_modified_gmt']);
//    unset($product_vars['comment_count']);
//
//    // get reference custom fields
//    $ref_fields = get_post_custom( $ref_id );
//
//    // get reference taxonomies
//    $all_tax = get_object_taxonomies('product');
//    if ( ! empty($all_tax) ) {
//        $ref_tax = wp_get_object_terms( $ref_id, $all_tax, array('fields' => 'all') );
//    }
//
//    //Возможно удалить (у нас уже есть массив id изображений, из которых нужно создать товары)//////////////////////
//
//    // skip reference thumbnail and images passed as second param in function
//    $skip_images = array_merge( (array)$skip_images, array($ref_thumb) );
//
    $args = array(
        //'post__not_in' => $skip_images,
        'post__in' => $image_ids,
        //'cache_results' => '0',
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => '-1',
        'post_status' => 'inherit',
    );
    $images = new WP_Query( $args );
//
    $errors = array();
//    if($reference->is_type('variable')) {
//        foreach ($reference->get_available_variations() as $variation) {
//            // Variation ID
//            $variation_id = $variation['variation_id'];
//            $attributes = array();
//            foreach ($variation['attributes'] as $attribute) {
//                array_push($attributes, $attribute);
//            }
//        }
//    }
//    return print_r($reference->get_available_variations(), true);

//
    // start loop through images
    $product_attributes = array();
    //Пробегаемся по переданным вариациям в первый раз, потому что нужно добавить к самому товару нужные значения атрибутов
    foreach ($variations as $variation)
    {
        array_push($product_attributes, $variation['attribute']);
    }

//    $ref_vars = $reference->get_available_variations();
//    foreach ($ref_vars as $ref_var)
//        return print_r($ref_var, true);

    if ( $images->have_posts() ) :

        // after that any other function call will fail
        //set_transient( 'convert_images_to_products_done', 1);

        //global $wpdb;

        while( $images->have_posts() ) :
            $images->the_post();
            global $post;
            $image = $post->ID;
//            $excerpt = get_the_excerpt();
//            if ( empty($excerpt) ) $excerpt = get_the_title();
            $title = get_the_title();
            //array_push($result, $title);

            $product = new WC_Product_Variable();
            $product->set_name($title);
            $product->set_image_id($image);
            $product->set_category_ids(array($category));
            //$product->set_manage_stock(true);
            $product->set_stock_status('');
            //$product->set_stock_quantity(1);

            //Create the attribute object
            $attribute = new WC_Product_Attribute();
            //pa_size tax id
            $attribute->set_id( 0 );
            //pa_size slug
            $attribute->set_name( 'format' );
            //Set terms slugs

            $attribute->set_position( 0 );
            //If enabled
            $attribute->set_visible( 1 );
            //If we are going to use attribute in order to generate variations
            $attribute->set_variation( 1 );

            $attribute->set_options( $product_attributes );

            $product->set_attributes(array($attribute));

            $product->save();
            //return $product->get_id();
            $i = 1;
            //Пробегаемся по вариациям еще раз для создания вариаций
        //return $product->get_id();
            foreach ($variations as $variation)
            {
                $variation_post = array(
                    'post_title'  => $product->get_name(),
                    'post_name'   => 'product-'.$product->get_id().'-variation-'.$i,
                    'post_status' => 'publish',
                    'post_parent' => $product->get_id(),
                    'post_type'   => 'product_variation',
                    'guid'        => $product->get_permalink()
                );

                // Creating the product variation
                $variation_id = wp_insert_post( $variation_post );
                $i++;

                // Get an instance of the WC_Product_Variation object
                $variation_prod = new WC_Product_Variation( $variation_id );

                $variation_prod->set_parent_id($product->get_id());

                $variation_prod->set_attributes(array('format' => $variation['attribute']));
                //return print_r($variation_prod, true);
                //$variation_prod->set_attribute_summary('pa_format:'.$variation['attribute']);
                //$variation_prod->set_variation_attributes(array('pa_format' => $variation['attribute']));
                //$variation_prod->set_attributes(array('id' => 1, 'option' => $variation['attribute']));
//                $variation_prod->set_attributes(array('format' => $variation['attribute']));
//                $variation_prod->set_attributes(array($variation['attribute']));

                $variation_prod->set_regular_price($variation['price']);
                $variation_prod->set_image_id($image);

                //$variation_prod->set_manage_stock(true);
                $variation_prod->set_stock_status('instock');

                //file_put_contents('log.txt', print_r(	$variation_prod->get_attributes(),true ));

                $variation_prod->save();
            }

//
//            $product_vars['post_title'] = get_the_title();
//            $product_vars['post_excerpt'] = $excerpt;
//            $product = wp_insert_post( $product_vars );
//
//            if ( intval($product) ) {
//
//                // insert custom fields
//                if ( ! empty($ref_fields) ) {
//                    $meta_insert_query = "INSERT INTO $wpdb->postmeta ";
//            $meta_insert_query .= "(meta_key, meta_value) VALUES ";
//                    $values = '';
//                    foreach ( $ref_fields as $key => $array ) {
//                        if ( $key != '_thumbnail_id' ) {
//                            foreach ( $array as $value ) {
//                                if ( $values != '' ) $values .= ', ';
//                                $values .= $wpdb->prepare( '(%s, %s)', $key, maybe_serialize($value) );
//                            }
//                        }
//                    }
//                    if ( $values != '' ) {
//                        $meta_insert_query .= $values;
//                        if ( ! $wpdb->query( $meta_insert_query ) ) {
//                            $error = 'Fail on inserting meta query for product ';
//                            $error .= $product . '. Query: ' . $meta_insert_query;
//                            $errors[] =  $error;
//                        }
//                    }
//                }
//
//                // insert taxonomies
//                if ( ! empty($ref_tax) && ! is_wp_error( $ref_tax ) ) {
//                    $taxonomies = array();
//                    foreach ( $ref_tax as $term ) {
//                        if ( ! isset($taxonomies[$term->taxonomy]) )
//                            $taxonomies[$term->taxonomy] = array();
//                        $taxonomies[$term->taxonomy][] = $term->slug;
//                    }
//                    foreach ( $taxonomies as $tax => $terms ) {
//                        $set_tax = wp_set_post_terms( $product, $terms, $tax, false );
//                        if ( ! is_array($set_tax) ) {
//                            $error =  'Fail on insert terms of taxonomy ';
//                            $error .=  $tax . ' for product' . $product;
//                            if ( is_string( $set_tax ) )
//                                $error .= ' First offending term ' . $set_tax;
//                            if ( is_wp_error($set_tax) )
//                                $error .= ' Error: ' . $set_tax->get_error_message();
//                            $errors[] = $error;
//                        }
//                    }
//                }
//
//                if ( ! set_post_thumbnail( $product, $image ) ) {
//                    $error = 'Set thumbnail failed for product ';
//                    $error .= $product . ' image ' . $image;
//                    $errors[] = $error;
//                }
//            } else {
//                $errors[] = 'Insert post failed for image with id ' . $image;
//            }
//
        endwhile;
//
    else :

        return 'no_media';

    endif;
//
    wp_reset_postdata();

    return 'true';
//
//    if ( ! empty($errors) )
//        wp_die('<p>' . implode('</p><p>', $errors) . '</p>');

}