<?php
/**
 * Storefront WooCommerce hooks
 *
 * @package storefront
 */

/**
 * Homepage
 *
 * @see  storefront_product_categories()
 * @see  storefront_recent_products()
 * @see  storefront_featured_products()
 * @see  storefront_popular_products()
 * @see  storefront_on_sale_products()
 * @see  storefront_best_selling_products()
 */
add_action( 'homepage', 'storefront_product_categories', 20 );
add_action( 'homepage', 'storefront_recent_products', 30 );
add_action( 'homepage', 'storefront_featured_products', 40 );
add_action( 'homepage', 'storefront_popular_products', 50 );
add_action( 'homepage', 'storefront_on_sale_products', 60 );
add_action( 'homepage', 'storefront_best_selling_products', 70 );

/**
 * Layout
 *
 * @see  storefront_before_content()
 * @see  storefront_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  storefront_shop_messages()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_main_content', 'storefront_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'storefront_after_content', 10 );
add_action( 'storefront_content_top', 'storefront_shop_messages', 15 );
add_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );

add_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
//add_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
//add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
add_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );

//add_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
//add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
//add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
//add_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
//add_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );

add_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );

// Legacy WooCommerce columns filter.
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.3', '<' ) ) {
	add_filter( 'loop_shop_columns', 'storefront_loop_columns' );
	add_action( 'woocommerce_before_shop_loop', 'storefront_product_columns_wrapper', 40 );
	add_action( 'woocommerce_after_shop_loop', 'storefront_product_columns_wrapper_close', 40 );
}

/**
 * Products
 *
 * @see storefront_edit_post_link()
 * @see storefront_upsell_display()
 * @see storefront_single_product_pagination()
 * @see storefront_sticky_single_add_to_cart()
 */
add_action( 'woocommerce_single_product_summary', 'storefront_edit_post_link', 60 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );

add_action( 'woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30 );
add_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );

/**
 * Header
 *
 * @see storefront_product_search()
 * @see storefront_header_cart()
 */
//add_action( 'storefront_header', 'storefront_product_search', 40 );
//add_action( 'storefront_header', 'storefront_header_cart', 10 );

/**
 * Cart fragment
 *
 * @see storefront_cart_link_fragment()
 */
if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'storefront_cart_link_fragment' );
} else {
	add_filter( 'add_to_cart_fragments', 'storefront_cart_link_fragment' );
}

/**
 * Integrations
 *
 * @see storefront_woocommerce_brands_archive()
 * @see storefront_woocommerce_brands_single()
 * @see storefront_woocommerce_brands_homepage_section()
 */
if ( class_exists( 'WC_Brands' ) ) {
	add_action( 'woocommerce_archive_description', 'storefront_woocommerce_brands_archive', 5 );
	add_action( 'woocommerce_single_product_summary', 'storefront_woocommerce_brands_single', 4 );
	add_action( 'homepage', 'storefront_woocommerce_brands_homepage_section', 80 );
}

//Переместить закрывающий тег ссылки на товар перед выводом формата
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15);

//Показать атрибуты на странице каталога
add_action( 'woocommerce_shop_loop_item_title', 'available_attributes', 20 );

add_filter( 'woocommerce_product_add_to_cart_text', 'my_custom_cart_button_text', 10, 2 );

//Кастомное отображение цены вариативных товаров
add_filter('woocommerce_variable_price_html', 'custom_variation_price', 10, 2);

//Remove product meta
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

//Remove similar products
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

//add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
add_action('woocommerce_single_variation', 'woocommerce_template_single_price', 15);

//Цена вариации
remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10);

add_action('woocommerce_before_cart', 'cart_title', 10);

//Убрать список товара и итоговую сумму со страницы оформления заказа
remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );

add_action('woocommerce_before_checkout_form', 'checkout_title', 15);

add_action('woocommerce_before_account_navigation', 'account_title', 10);

//Виджет корзины в хедере
remove_action( 'woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10 );
add_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal_custom', 10);

//Описание в single product
//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 );

//Переносим изображение single product под название товара
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

//add_action('woocommerce_single_product_summary', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_show_product_images', 10);
/****************************************************************/
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

//add_action('woocommerce_after_single_product_summary', 'woocommerce_template_single_price', 10);

//remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

//add_action( 'woocommerce_before_customer_login_form', 'custom_login_logo', 15);
//add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 20);