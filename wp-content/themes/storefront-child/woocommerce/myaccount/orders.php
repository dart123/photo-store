<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php //if ( $has_orders ) : ?>
	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
            //$all_items = array();

            $user_role = wordpress_get_current_role();
            if ($user_role == 'administrator' || $user_role == 'kindergarten_admin')
            {
                $orders_to_loop = array();
                if ($user_role == 'administrator')
                {
                    $users_to_show = get_users(array('fields' => array('ID')));
                }

                elseif ($user_role == 'kindergarten_admin')
                {
                    //Kindergarten admin data
                    $admin_group_num = get_user_meta(get_current_user_id(), 'group_number', true);
                    $admin_kindergarten_category = get_kindergarten_cat_from_group_num($admin_group_num);
//print('<pre>'.print_r($admin_kindergarten_category, 1).'</pre>');
                    //list of users
                    $users_to_show = get_users(array('fields' => array( 'ID' )));
                    //delete every user that's not in current kindergarten
                    foreach ($users_to_show as $key => $user_obj)
                    {
                        //user kindergarten
                        $user_group_num = get_user_meta($user_obj->ID, 'group_number', true);
                        if (isset($user_group_num) && !empty($user_group_num))
                        {
                            $user_kindergarten_category = get_kindergarten_cat_from_group_num($user_group_num);
                            if ($admin_kindergarten_category->name != $user_kindergarten_category->name)
                                unset($users_to_show[$key]);
                        }
                        //Если у юзера нет номера группы, не отображаем заказы пользователя
                        else
                            unset($users_to_show[$key]);
                    }
                }
                /************************/
                //print('<pre>'.print_r($users_to_show, 1).'</pre>');
                foreach ($users_to_show as $user)
                {
                    //get order for this user
                    $args = array(
                        'customer_id' => $user->ID,
                    );
                    $customer_orders = wc_get_orders( $args );
                    //echo 'customer_order_count: '.count($customer_orders).'<br>';
                    if (count($customer_orders) > 0)
                        foreach ($customer_orders as $customer_order)
                            array_push($orders_to_loop, $customer_order);
                }


                $array_to_loop = $orders_to_loop;
            }
            else
                $array_to_loop = $customer_orders->orders;

            $item_amount = 0;
            foreach ($array_to_loop as $order)
            {
                $items = $order->get_items();
                $item_amount += count($items);
                //echo 'date_created: '.	$order-> get_date_created().'<br>';
            }
            //echo 'users: '.count($users_to_show);
            //print("<pre>".print_r($order,true)."</pre>");

            foreach ( $array_to_loop as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();

                $order_items = $order->get_items();

                $order_user_id = $order->get_customer_id();

                $order_user_name = get_user_by('id', $order_user_id)->display_name;

                foreach ( $order_items as $item ) {
                    //array_push($all_items, $item);
                    $item_quantity = $item->get_quantity( );
                    $product_name = $item->get_name();
                    $product_id = $item->get_product_id();
                    $product = wc_get_product($product_id);
                    $product_image = $product->get_image();

                    if ( $product -> is_type( 'variable' )) {
                        $variation_id = $item->get_variation_id();
                        $variation = wc_get_product($variation_id);
                        $variation_format = $variation->get_attribute('pa_format');
                       // $variation_attributes = $variation->get_variation_attributes();
                    }
                //}
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

<!--							--><?php //elseif ( 'order-number' === $column_id ) : ?>
<!--								<a href="--><?php //echo esc_url( $order->get_view_order_url() ); ?><!--">-->
<!--									--><?php //echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
<!--								</a>-->

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

<!--							--><?php //elseif ( 'order-status' === $column_id ) : ?>
<!--								--><?php //echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

<!--							--><?php //elseif ( 'order-total' === $column_id ) : ?>
<!--								--><?php
//								/* translators: 1: formatted order total 2: total order items */
//								echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
//								?>

<!--							--><?php //elseif ( 'order-actions' === $column_id ) : ?>
<!--								--><?php
//								$actions = wc_get_account_orders_actions( $order );
//
//								if ( ! empty( $actions ) ) {
//									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
//										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
//									}
//								}
////								?>
<!--                            --><?php //elseif ( 'order-id' === $column_id ) : ?>
<!--                                --><?php
//                                    $order_id = $order->get_order_number();
//                                    echo '<span>'.$order_id.'</span>';
//                                ?>
                            <?php elseif ('order-user' === $column_id) : ?>
                                <?php
                                    echo esc_html($order_user_name);
                                ?>

                            <?php elseif ('product-quantity' === $column_id) : ?>
                                <?php
                                    echo esc_html($item_quantity);
                                ?>
                            <?php elseif ('product-photo' === $column_id) : ?>
                                <?php
                                    echo $product_image;
                                ?>
                            <?php elseif ('product-name' === $column_id) : ?>
                                <?php
                                    echo esc_html($product_name);
                                ?>
                            <?php elseif ('product-format' === $column_id) : ?>
                                <?php
                                    if ( $product -> is_type( 'variable' )) {
                                        echo esc_html($variation_format);
                                    }
                                    else
                                        echo '';
                                ?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<?php
			} }
			?>
		</tbody>
	</table>
<?php echo 'Количество купленных товаров: '.$item_amount;?>
	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages/*ceil($item_amount / 10)*/ ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval($customer_orders->max_num_pages)/*ceil($item_amount / 10) )*/ !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php //else : ?>
<!--	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">-->
<!--		<a class="woocommerce-Button button" href="--><?php //echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?><!--">-->
<!--			--><?php //esc_html_e( 'Browse products', 'woocommerce' ); ?>
<!--		</a>-->
<!--		--><?php //esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
<!--	</div>-->
<?php //endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
