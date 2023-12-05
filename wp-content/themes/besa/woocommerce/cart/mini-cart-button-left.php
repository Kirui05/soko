<?php   
	global $woocommerce; 
    $_id = besa_tbay_random_key();
    
    extract($args);
    
?>
<div class="tbay-topcart left-right">
 	<div id="cart-<?php echo esc_attr($_id); ?>" class="cart-dropdown dropdown">
        <a class="dropdown-toggle mini-cart v2" data-offcanvas="offcanvas-left" data-toggle="dropdown" aria-expanded="true" role="button" aria-haspopup="true" data-delay="0" href="javascript:void(0);" title="<?php esc_attr_e('View your shopping cart', 'besa'); ?>">
			<?php  besa_tbay_minicart_button( $icon_array, $show_title_mini_cart, $title_mini_cart, $price_mini_cart ); ?>
        </a>    
        <div class="dropdown-menu"></div>
    </div> 
	<?php besa_tbay_get_page_templates_parts('offcanvas-cart', 'left'); ?>
</div>    

