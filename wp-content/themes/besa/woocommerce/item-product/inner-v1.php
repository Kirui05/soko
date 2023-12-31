<?php 
global $product;

$flash_sales 	= isset($flash_sales) ? $flash_sales : false;
$end_date 		= isset($end_date) ? $end_date : '';

$countdown_title 		= isset($countdown_title) ? $countdown_title : '';

$countdown 		= isset($countdown) ? $countdown : false;
$class = array();
$class_flash_sale = besa_tbay_class_flash_sale($flash_sales);
array_push($class, $class_flash_sale);
$skin = besa_tbay_get_theme();
?>
<div <?php besa_tbay_product_class($class); ?> data-product-id="<?php echo esc_attr($product->get_id()); ?>">
	<?php 
		/**
		* besa_woocommerce_before_product_block_grid hook
		*
		* @hooked besa_woocommerce_add_quantity_mode_grid - 10
		*/
		do_action( 'besa_woocommerce_before_product_block_grid' );
	?>

	<?php
		if ($skin === 'style1') {
			?>
				<div class="product-top">
					<?php
						/**
						* tbay_woocommerce_before_content_product hook
						*
						* @hooked woocommerce_show_product_loop_sale_flash - 10
						*/
						do_action( 'tbay_woocommerce_before_content_product' );
					?>
				</div>
			<?php
		}
	?>
	
	<div class="product-content">
		<?php 
			/**
			 * Hook: woocommerce_before_shop_loop_item.
			 *
			 * @hooked woocommerce_template_loop_product_link_open - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item' );
		?>
		<div class="block-inner">
			<figure class="image <?php besa_product_block_image_class(); ?>">
				<a title="<?php the_title_attribute(); ?>" href="<?php echo the_permalink(); ?>" class="product-image">
					<?php
						/**
						* woocommerce_before_shop_loop_item_title hook
						*
						* @hooked woocommerce_template_loop_product_thumbnail - 10
						*/
						do_action( 'woocommerce_before_shop_loop_item_title' );
					?>
				</a>

				<?php
					if ($skin === 'style2') {
						?>
							<div class="product-top">
								<?php
									/**
									* tbay_woocommerce_before_content_product hook
									*
									* @hooked woocommerce_show_product_loop_sale_flash - 10
									*/
									do_action( 'tbay_woocommerce_before_content_product' );
								?>
							</div>
						<?php
					}
				?>

				<?php 
					/**
					* besa_tbay_after_shop_loop_item_title hook
					*
					* @hooked besa_tbay_add_slider_image - 10
					*/
					do_action( 'besa_tbay_after_shop_loop_item_title' );
				?>
			<?php besa_tbay_item_deal_ended_flash_sale($flash_sales, $end_date); ?>
			</figure>
			<div class="group-buttons">	
				<?php 
					/**
					* besa_woocommerce_group_buttons hook
					*
					* @hooked woocommerce_template_loop_add_to_cart - 10
					* @hooked besa_the_yith_wishlist - 20
					* @hooked besa_the_quick_view - 30
					* @hooked besa_the_yith_compare - 40
					*/
					do_action( 'besa_woocommerce_group_buttons', $product->get_id() );
				?>
		    </div>
		</div>
		<?php besa_woo_product_time_countdown($countdown, $countdown_title); ?>
		
					
		<div class="caption">
			<?php 
				do_action('besa_woo_before_shop_loop_item_caption');
			?>
						
			<?php besa_the_product_name(); ?>
			<?php
				/**
				* besa_woocommerce_loop_item_rating hook
				*
				* @hooked woocommerce_template_loop_rating - 10
				*/
				do_action( 'besa_woocommerce_loop_item_rating');
			?>
			
			<?php 
				global $product;
				$price = $product->get_price_html();
				if ($price !== '') {
					?>
					<div class="price-wrapper">
						<?php
							/**
							* woocommerce_after_shop_loop_item_title hook
							*
							* @hooked woocommerce_template_loop_price - 10
							*/
							do_action( 'woocommerce_after_shop_loop_item_title');
						?>
					</div>
					<?php
				}
			?> 
			<?php 
				do_action('besa_woo_after_shop_loop_item_caption');
			?>
		</div>
		<?php besa_tbay_stock_flash_sale($flash_sales); ?>
		<?php besa_woo_product_time_countdown_stock($countdown); ?>
		<?php
			do_action( 'besa_woocommerce_after_product_block_grid' );
		?>
    </div>
    
	
	<?php 
		/**
		* Hook: woocommerce_after_shop_loop_item.
		*/
		do_action( 'woocommerce_after_shop_loop_item' );
	?> 
</div>
