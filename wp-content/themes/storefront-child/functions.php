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

add_filter('woocommerce_checkout_fields', 'custom_checkout_fields');
function custom_checkout_fields($fields)
{
//    $fields['account']['account_username']['label'] = '№ Учреждения';
//    $fields['account']['account_username']['placeholder'] = '№ Учреждения';
    //unset($fields['account']['account_username']);
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
    return $fields;
}

//Убрать способы оплаты со страницы оформления заказа
//add_filter('woocommerce_cart_needs_payment', '__return_false');

//add_action( /*'woocommerce_checkout_order_processed'*/'woocommerce_payment_complete', 'send_customer_invoice',  10, 1 );
//add_action('woocommerce_single_product_summary', 'send_customer_invoice', 25);
//function is_express_delivery( $order_id ){
//
//    echo 'aaaaa';
//    //$order = new WC_Order( $order_id );
//    //You can do here whatever you want
//
//}
//function send_customer_invoice($order_id){//$order_id, $order ) {
//
//    $order = wc_get_order( $order_id );
//    $order->update_status('pending');
//
//    $heading = 'Подтверждение заказа';
//    $subject = 'Подтверждение заказа';
//
//    // Get WooCommerce email objects
//    $mailer = WC()->mailer()->get_emails();
//
//    // Use one of the active emails e.g. "Customer_Completed_Order"
//    // Wont work if you choose an object that is not active
//    // Assign heading & subject to chosen object
//    $mailer['WC_Email_Customer_Invoice']->heading = $heading;
//    $mailer['WC_Email_Customer_Invoice']->settings['heading'] = $heading;
//    $mailer['WC_Email_Customer_Invoice']->subject = $subject;
//    $mailer['WC_Email_Customer_Invoice']->settings['subject'] = $subject;
//
//    //echo '<pre>'.print_r($mailer['WC_Email_Customer_Invoice'], true).'</pre>';
//    // Send the email with custom heading & subject
//    $mailer['WC_Email_Customer_Invoice']->trigger( $order_id );
//
//    // To add email content use https://businessbloomer.com/woocommerce-add-extra-content-order-email/
//    // You have to use the email ID chosen above and also that $order->get_status() == "refused"
//
//}
function remove_links_my_account( $items ) {
    $new_items = $items;
    //var_dump($new_items);
//var_dump(get_user_meta(get_current_user_id(), 'group_number', true));
    unset($new_items['downloads']);
    unset($new_items['edit-address']);
    unset($new_items['dashboard']);

    $new_items['orders'] = 'История покупок';
    $new_items['edit-account'] = 'Личные данные';

    //var_dump($new_items);
    return $new_items;
}

add_filter( 'woocommerce_account_menu_items', 'remove_links_my_account' );

//Добавление колонок в таблицу в истории заказов
function new_orders_columns( $columns = array() ) {

       // Unsets the columns which you want to hide
    unset( $columns['order-number'] );
//        unset( $columns['order-date'] );
    unset( $columns['order-status'] );
    unset( $columns['order-total'] );
    unset( $columns['order-actions'] );

    $user_role = wordpress_get_current_role();
    if ($user_role == 'kindergarten_admin' || $user_role == 'administrator')
        $columns['order-user'] = 'Пользователь';

    $columns['product-photo'] = 'Фото';
    $columns['product-name'] = 'Товар';
    $columns['product-quantity'] = 'Количество товара';
    $columns['product-format'] = 'Формат';

    return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'new_orders_columns' );

function woocommerce_non_registered_redirect() {
    if ( !is_user_logged_in()
        && (is_woocommerce() || is_cart() || is_checkout() )
    ) {
        wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id')));
        exit;
    }
}
add_action('template_redirect', 'woocommerce_non_registered_redirect');

//Добавляем поле "номер группы" в форму регистрации
function woocommerce_edit_my_account_page() {
    return apply_filters( 'woocommerce_forms_field', array(
        'group_number' => array(
            'type'        => 'text',
            'label'       => 'Название/номер группы',
            'placeholder' => '',
            'required'    => true,
        ),
    ) );
}
function edit_my_account_page_woocommerce() {
    $fields = woocommerce_edit_my_account_page();

    foreach ( $fields as $key => $field_args ) {
        woocommerce_form_field( $key, $field_args );
    }
}
add_action( 'woocommerce_register_form', 'edit_my_account_page_woocommerce', 15 );

//Валидация поля "номер группы"
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['group_number'] ) && empty( $_POST['group_number'] ) ) {
        $validation_errors->add( 'group_number_error', 'Номер группы является обязательным для заполнения!', 'woocommerce' );
    }
    return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

//Сохранение поля "номер группы" в базу данных
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['group_number'] ) ) {

        $result = update_user_meta( $customer_id, 'group_number', sanitize_text_field( $_POST['group_number'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

//Отображение поля "номер группы" в админке
function wordpress_show_extra_profile_fields( $user )
{
    $group_number = get_user_meta($user->ID, 'group_number', true);
    ?>
    <h3><?php echo 'Название/номер группы'; ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="group_num">Название/номер группы</label></th>
            <td>
                <input type="text"
                       required
                       step="1"
                       id="group_num"
                       name="group_num"
                       value="<?php echo esc_attr($group_number); ?>"
                       class="regular-text"
                />
            </td>
        </tr>
    </table>

<?php

}
add_action( 'edit_user_profile', 'wordpress_show_extra_profile_fields' );

//Валидация номера группы в админке
function wordpress_user_profile_update_errors( $errors, $update, $user ) {
    if ( empty( $_POST['group_num'] ) ) {
        $errors->add( 'group_num_error', 'Пожалуйста, введите номер группы.');
    }
}
add_action( 'user_profile_update_errors', 'wordpress_user_profile_update_errors', 10, 3 );

//Сохранение номера группы в бд
function wordpress_update_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    if ( ! empty( $_POST['group_num'] ) ) {
        update_user_meta( $user_id, 'group_number', $_POST['group_num'] );
    }
}
add_action( 'edit_user_profile_update', 'wordpress_update_profile_fields' );

function wordpress_get_current_role()
{
    if (!isset(wp_get_current_user()->roles) || empty(wp_get_current_user()->roles))
        return false;
    $roles = wp_get_current_user()->roles;
    $current_role = array_shift($roles);
    //var_dump($current_role);
    return $current_role;
}
function get_kindergarten_cat_from_group_num($group_num)
{
    if (isset($group_num) && !empty($group_num)) {
        //Получаем категорию группы пользователя по его названию группы
        $user_group = get_term_by('slug', $group_num, 'product_cat');
        //ID Родителя группы пользователя
        $user_group_ancestors = get_ancestors($user_group->term_id, 'product_cat');
        $kindergarten_id = array_shift($user_group_ancestors);

        //Категория детского сада
        $kindergarten_category = get_term_by('id', $kindergarten_id, 'product_cat');
    }
    if (isset($kindergarten_category) && !empty($kindergarten_category))
        return $kindergarten_category;
    else
        return false;
}
//Функция, проверяющая, можно ли отображать данный товар для данного пользователя
function is_product_allowed($user, $product)
{
    $cat_ids = $product->get_category_ids();
    $user_group_num = get_user_meta($user->ID, 'group_number', true);
    $user_role = wordpress_get_current_role();
    $is_allowed = false;
    if ($user_role && !empty($user_role))
    {
        if ($user_role == 'administrator')
            return true;
        elseif ($user_role == 'kindergarten_admin')
        {
            if (isset($user_group_num) && !empty($user_group_num))
            {
                $kindergarten_category = get_kindergarten_cat_from_group_num($user_group_num);

                //Проходимся по всем категориям данного продукта
                foreach ($cat_ids as $cat_id)
                {
                    //Родители категории данного продукта
                    $product_cat_ancestors = get_ancestors( $cat_id, 'product_cat' );
                    //Проход по всем родителям
                    foreach ($product_cat_ancestors as $ancestor)
                    {
                        //Получаем категорию родителя
                        $ancestor_category = get_term_by('id', $ancestor, 'product_cat');
                        //Сравниваем имена родителей (имена дет.садов)
                        if ($ancestor_category->name == $kindergarten_category->name)
                            return true;
                    }

                }
                return $is_allowed;
            }
            else
                return false;
        }
        else //Любая другая роль
        {
            if (isset($user_group_num) && !empty($user_group_num)) {
                foreach ($cat_ids as $cat_id) //Проходимся по всем категориям товара
                {
                    $category = get_term_by('id', $cat_id, 'product_cat'); //Получаем объект категории
                    if ($category->name == $user_group_num)
                    {
                        return true;
                    }
                }
                return $is_allowed;
            }
            else
                return false;
        }
    }
}
//Редирект после логина
function woocommerce_login_redirect( $redirect) {
    $redirect_page_id = url_to_postid( $redirect );
    $checkout_page_id = wc_get_page_id( 'checkout' );

    if( $redirect_page_id == $checkout_page_id ) {
        return $redirect;
    }

    return wc_get_page_permalink( 'shop' );
}

add_filter( 'woocommerce_login_redirect', 'woocommerce_login_redirect' );

//Редирект после регистрации
function woocommerce_register_redirect( $redirect ) {
    return wc_get_page_permalink( 'shop' );
}
add_filter( 'woocommerce_registration_redirect', 'woocommerce_register_redirect' );

/**
 * Filer WooCommerce Flexslider options - Add Navigation Arrows
 */
function sf_update_woo_flexslider_options( $options ) {

    $options['directionNav'] = true;
    $options['controlNav'] = false;

    return $options;
}
add_filter( 'woocommerce_single_product_carousel_options', 'sf_update_woo_flexslider_options' );

function remove_slider_theme_support()
{
//    remove_theme_support( 'wc-product-gallery-zoom' );
//    remove_theme_support( 'wc-product-gallery-lightbox' );
    remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'wp', 'remove_slider_theme_support', 99 );


//Изменить размер изображений в галерее
//add_filter( 'woocommerce_get_image_size_gallery_image', function( $size ) {
//    return array(
//        'width' => 1000,
//        'height' => 1000,
//        'crop' => 1,
//    );
//} );
//add_filter( 'woocommerce_gallery_thumbnail_size', function( $size ) {
//    return 'full';
//} );
add_filter( 'woocommerce_gallery_image_size', function( $size ) {
    return 'full';
} );


/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */
