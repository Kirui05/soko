<?php 
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$tabs_layout   =  apply_filters( 'besa_woo_tabs_style_single_product', 10, 2 );

if( wp_is_mobile() && besa_tbay_get_config('enable_tabs_mobile', false) ) {
	wc_get_template( 'single-product/tabs/tabs-mobile.php' );
	return;
}

if( $tabs_layout !== 'fulltext' ) {
	add_filter('woocommerce_product_description_heading', '__return_empty_string', 10, 1);
	add_filter('besa_woocommerce_product_more_product_heading', '__return_empty_string', 10, 1);
}

if( isset($tabs_layout) ) {
	if( $tabs_layout == 'tabs' ) {
		wc_get_template( 'single-product/tabs/tabs-default.php' );
		return;
	} else {
		wc_get_template( 'single-product/tabs/tabs-'.$tabs_layout.'.php' );
		return;
	}
}