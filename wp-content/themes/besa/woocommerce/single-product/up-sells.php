<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

if ( sizeof( $upsells ) == 0 ) {
	return;
}


if( isset($_GET['related_columns']) ) { 
	$woocommerce_loop['columns'] = $_GET['related_columns']; 
} else {
	$woocommerce_loop['columns'] = besa_tbay_get_config('releated_product_columns', 4);
}

$columns_desktopsmall = 4;
$columns_tablet = 3;
$columns_mobile = 2;

$rows = apply_filters( 'besa_woo_row_single_full', false,2 ); 
if($rows) {
	$rows = 4;
	$woocommerce_loop['columns'] = 1;
	$columns_desktopsmall = 1;
	$columns_tablet = 1;
	$columns_mobile = 1;
} else {
	$rows = 1;
}

$show_product_upsells = besa_tbay_get_config('enable_product_upsells', true);

if ( $upsells && $show_product_upsells ) : ?>

	<div class="upsells tbay-element tbay-element-product">
		<?php 
		$heading = apply_filters( 'woocommerce_product_upsells_products_heading', esc_html__( 'You may also like&hellip;', 'besa' ) );
		if ( $heading ) :?>
			<h3 class="heading-tbay-title"><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>

		<div class="tbay-element-content woocommerce">
		<?php  wc_get_template( 'layout-products/carousel-related.php' , array( 'loops'=>$upsells,'rows' => $rows, 'pagi_type' => 'no', 'nav_type' => 'yes','columns'=>$woocommerce_loop['columns'],'screen_desktop'=>$woocommerce_loop['columns'],'screen_desktopsmall'=>$columns_desktopsmall,'screen_tablet'=>$columns_tablet,'screen_mobile'=>$columns_mobile ) ); ?>
		</div>
	</div>

<?php endif;

wp_reset_postdata();
