<?php 
/**
 * Templates Name: Elementor
 * Widget: Mini Cart
 */

$active = besa_catalog_mode_active();

if ( null === WC()->cart || $active ) {
    return;
}
?>
<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php $this->render_woocommerce_mini_cart(); ?>
</div>