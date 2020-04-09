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
    if( 'woo-products-from-images.php' != $hook )
        return;
    wp_enqueue_style('woo-products-from-img-style', plugins_url('css/style.css', __FILE__), '', '1.0.0', 'screen');
    wp_enqueue_script( 'woo-products-from-img-script', plugins_url('js/script.js', __FILE__), array('jquery'), '1.0.0' );

    $generate_products_nonce = wp_create_nonce( 'generate_products_example' );
    wp_localize_script( 'woo-products-from-img-script', 'my_ajax_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => $generate_products_nonce,
    ) );
}
add_action('admin_enqueue_scripts', 'plugin_scripts');

function generate_products()
{
    if (isset($_POST) && !empty($_POST))
    {
        if (isset($_POST['product_ids']) && !empty($_POST['product_ids']))
        {
            echo json_encode($_POST['product_ids']);
        }
//        else
//            echo 'false';
    }
}
add_action('init', 'generate_products');
//$title_nonce = wp_create_nonce( 'title_example' );