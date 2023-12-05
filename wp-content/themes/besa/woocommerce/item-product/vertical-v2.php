<?php 
global $product;

$product_style = isset($product_style) ? $product_style : '';
$skin = besa_tbay_get_theme();
?>
<div class="product-block product <?php echo esc_attr($product_style); ?> <?php besa_is_product_variable_sale(); ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
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
		<div class="block-inner">
			<?php 
				/**
				* Hook: woocommerce_before_shop_loop_item.
				*
				* @hooked woocommerce_template_loop_product_link_open - 10
				*/
				do_action( 'woocommerce_before_shop_loop_item' );
			?>
			<figure class="image ">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php echo trim($product->get_image()); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</figure>
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
		</div>
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
				/**
				* Hook: woocommerce_after_shop_loop_item.
				*/
				do_action( 'woocommerce_after_shop_loop_item' );
			?>
		</div>
    </div>
</div>
