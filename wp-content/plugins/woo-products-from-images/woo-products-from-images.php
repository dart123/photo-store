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
    if (isset($_POST['product_ids']) && !empty($_POST['product_ids']))
    {
        // REPLACE THE 0 IN FUNCTION PARAM BELOW WITH YOUR REFERENCE PRODUCT ID
        //
        //wpa_convert_images_to_products( 0 );
        echo json_encode($_POST['product_ids']);
    }
    else
        echo 'false';
    wp_die();
}
add_action( 'wp_ajax_generate_products', 'generate_products_ajax_handler' );

function wpa_convert_images_to_products($ref_id = 0, $skip_images = array() ) {

    // following line ensure that present function is run once
    if (  get_transient('convert_images_to_products_done') ) return;

    //try to remove php limits in execution time an memory
    @set_time_limit (0);
    @ini_set('memory_limit', -1);

    if ( ! post_type_exists( 'product' ) || ! $ref_id )
        wp_die('Reference post id is not valid or product post type is not registered.');

    $reference = get_post($ref_id);

    if ( ! $reference )
        wp_die('Given reference post id is not valid.');

    $ref_thumb = get_post_thumbnail_id( $ref_id );
    if ( ! is_int($ref_thumb) ) $ref_thumb = null;

    // get reference attributes
    $product_vars = get_object_vars($reference);
    unset($product_vars['ID']);
    unset($product_vars['post_date']);
    unset($product_vars['post_date_gmt']);
    unset($product_vars['post_modified']);
    unset($product_vars['post_modified_gmt']);
    unset($product_vars['comment_count']);

    // get reference custom fields
    $ref_fields = get_post_custom( $ref_id );

    // get reference taxonomies
    $all_tax = get_object_taxonomies('product');
    if ( ! empty($all_tax) ) {
        $ref_tax = wp_get_object_terms( $ref_id, $all_tax, array('fields' => 'all') );
    }

    // skip reference thumbnail and images passed as second param in function
    $skip_images = array_merge( (array)$skip_images, array($ref_thumb) );

    $args = array(
        'post__not_in' => $skip_images,
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => '-1',
        'post_status' => 'inherit',
    );
    $images = new WP_Query( $args );

    $errors = array();

    // start loop through images

    if ( $images->have_posts() ) :

        // after that any other function call will fail
        set_transient( 'convert_images_to_products_done', 1);

        global $wpdb;

        while( $images->have_posts() ) :
            $images->the_post();
            global $post;
            $image = $post->ID;
            $excerpt = get_the_excerpt();
            if ( empty($excerpt) ) $excerpt = get_the_title();

            $product_vars['post_title'] = get_the_title();
            $product_vars['post_excerpt'] = $excerpt;
            $product = wp_insert_post( $product_vars );

            if ( intval($product) ) {

                // insert custom fields
                if ( ! empty($ref_fields) ) {
                    $meta_insert_query = "INSERT INTO $wpdb->postmeta ";
            $meta_insert_query .= "(meta_key, meta_value) VALUES ";
                    $values = '';
                    foreach ( $ref_fields as $key => $array ) {
                        if ( $key != '_thumbnail_id' ) {
                            foreach ( $array as $value ) {
                                if ( $values != '' ) $values .= ', ';
                                $values .= $wpdb->prepare( '(%s, %s)', $key, maybe_serialize($value) );
                            }
                        }
                    }
                    if ( $values != '' ) {
                        $meta_insert_query .= $values;
                        if ( ! $wpdb->query( $meta_insert_query ) ) {
                            $error = 'Fail on inserting meta query for product ';
                            $error .= $product . '. Query: ' . $meta_insert_query;
                            $errors[] =  $error;
                        }
                    }
                }

                // insert taxonomies
                if ( ! empty($ref_tax) && ! is_wp_error( $ref_tax ) ) {
                    $taxonomies = array();
                    foreach ( $ref_tax as $term ) {
                        if ( ! isset($taxonomies[$term->taxonomy]) )
                            $taxonomies[$term->taxonomy] = array();
                        $taxonomies[$term->taxonomy][] = $term->slug;
                    }
                    foreach ( $taxonomies as $tax => $terms ) {
                        $set_tax = wp_set_post_terms( $product, $terms, $tax, false );
                        if ( ! is_array($set_tax) ) {
                            $error =  'Fail on insert terms of taxonomy ';
                            $error .=  $tax . ' for product' . $product;
                            if ( is_string( $set_tax ) )
                                $error .= ' First offending term ' . $set_tax;
                            if ( is_wp_error($set_tax) )
                                $error .= ' Error: ' . $set_tax->get_error_message();
                            $errors[] = $error;
                        }
                    }
                }

                if ( ! set_post_thumbnail( $product, $image ) ) {
                    $error = 'Set thumbnail failed for product ';
                    $error .= $product . ' image ' . $image;
                    $errors[] = $error;
                }
            } else {
                $errors[] = 'Insert post failed for image with id ' . $image;
            }

        endwhile;

    else :

        wp_die('You have no media.');

    endif;

    wp_reset_postdata();

    if ( ! empty($errors) )
        wp_die('<p>' . implode('</p><p>', $errors) . '</p>');

}