<?php
/**
 * Plugin Name: Advanced product generation for WooCommerce
 * Plugin URI:
 * Description: Generate WooCommerce products from images and more
 * Version: 1.0
 * Author: Alexander Zemyansky
 * Author URI: http://www.mywebsite.com
 */
function plugin_page_output()
{
    //echo plugins_url('style.css', __FILE__);
    include('admin-screen.php');
}
function plugin_scripts()
{
    wp_enqueue_media(); //everything needed for javascript media APIs
    wp_enqueue_style('woocommerce-products-from-img-style', plugins_url('css/style.css', __FILE__), '', '1.0.0', 'screen');

    wp_enqueue_script('woocommerce-products-from-img-script', plugins_url('js/script.js', __FILE__), array('jquery'), '1.0.0');
}
add_action( 'admin_enqueue_scripts', 'plugin_scripts', 10 );

function plugin_menu()
{
    add_submenu_page( 'woocommerce', 'Загрузка товаров', 'Загрузка товаров',
        'manage_woocommerce', 'woo-product-generation', 'plugin_page_output');
}
add_action( 'admin_menu', 'plugin_menu' );

//function save_attachment_id()
//{
//    if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) {
//        set_transient('media_selector_attachment_url', $_POST['image_attachment_id'], 10 * 1);
//    }
//}
//add_action('init','save_attachment_id');