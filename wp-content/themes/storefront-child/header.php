<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="header" class="site-header masthead" role="banner" style="<?php storefront_header_styles(); ?>">

		<?php
		/**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_header_container                 - 0
		 * @hooked storefront_skip_links                       - 5
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_header_container_close           - 41
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */
        storefront_primary_navigation_wrapper();
		?>
        <a class="header__logo" href="/">
            <img class="header__logo__img" src="/wp-content/themes/storefront-child/assets/images/custom/logo@2x.png">
            <div class="header__logo__title">
                <div>ФОТОСТУДИЯ</div> <div>ФОКУС</div>
            </div>
        </a>

		<nav id="site-navigation" class="main-navigation header__menu" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
        <?php
        wp_nav_menu(
            array(
                'theme_location'  => 'primary',
                'container_class' => 'primary-navigation',
            )
        );
        ?>

        </nav>

        <a class="header__menu__item-img bkg-img person" href="/profile"></a>
        <?php
        storefront_header_cart();
        ?>

        <a class="header__menu__item-img bkg-img menu" href="##"></a>
        <div class="mobile-menu">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'handheld',
                'menu_class'        => 'mobile-menu-ul text-left',
            ) );
            ?>
        </div>
<!--        <div class="mobile-menu-bg">-->
<!--        </div>-->

        <?php
        storefront_primary_navigation_wrapper_close();
		do_action( 'storefront_header' );
		?>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
