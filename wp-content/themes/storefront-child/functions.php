<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}
function wc_hide_page_title() {
    if( is_front_page() )
        remove_action( 'storefront_homepage', 'storefront_homepage_header', 10 );
}
add_action( 'woocommerce_show_page_title', 'wc_hide_page_title');

add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {

    $classes[] = 'bkg-img';

    return $classes;

}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

function woocommerce_template_product_description() {
    wc_get_template( 'single-product/tabs/description.php' );
}

//add_filter('woocommerce_checkout_fields', 'custom_checkout_fields');
//function custom_checkout_fields($fields)
//{
//    echo 'before: <pre>'.print_r($fields, true).'</pre>';
//    unset($fields['billing_country']);
//    unset($fields['billing_address_1']);
//    unset($fields['billing_address_2']);
//    unset($fields['billing_city']);
//    unset($fields['billing_state']);
//    unset($fields['billing_postcode']);
//
//    unset($fields['shipping_country']);
//    unset($fields['shipping_address_1']);
//    unset($fields['shipping_address_2']);
//    unset($fields['shipping_city']);
//    unset($fields['shipping_state']);
//    unset($fields['shipping_postcode']);
//
//    unset($fields['order']['order_comments']);
//
//    echo 'after: <pre>'.print_r($fields, true).'</pre>';
//    return $fields;
//}

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 );

add_filter('woocommerce_cart_needs_payment', '__return_false');
//function register_form_fields() {
//    return apply_filters( 'woocommerce_forms_field', array(
//        'woocommerce_my_account_page' => array(
//            'type'        => 'text',
//            'label'       => 'Название/номер группы',
//            'placeholder' => 'Название/номер группы',
//            'required'    => true,
//        ),
//    ) );
//}
//function custom_register_form() {
//    $fields = register_form_fields();
//    foreach ( $fields as $key => $field_args ) {
//        woocommerce_form_field( $key, $field_args );
//    }
//}
//
//function custom_login_form() {
//    $fields = register_form_fields();
//    foreach ( $fields as $key => $field_args ) {
//        woocommerce_form_field( $key, $field_args );
//    }
//}
//add_action( 'woocommerce_register_form', 'custom_register_form', 15 );
//
//add_action( 'woocommerce_login_form_start', 'custom_login_form', 15);

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
