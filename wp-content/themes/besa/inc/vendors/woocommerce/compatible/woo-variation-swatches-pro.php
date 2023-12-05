<?php

if (!besa_is_woo_variation_swatches_pro()) {
    return;
}

if (!function_exists('besa_quantity_swatches_pro_field_archive')) {
    function besa_quantity_swatches_pro_field_archive()
    {
        global $product;
        if (besa_is_quantity_field_archive()) {
            woocommerce_quantity_input(['min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity()]);
        }
    }
}

if (!function_exists('besa_variation_swatches_pro_group_button')) {
    add_action('besa_woo_before_shop_loop_item_caption', 'besa_variation_swatches_pro_group_button', 5);
    function besa_variation_swatches_pro_group_button()
    {
        $class_active = '';

        if (besa_woocommerce_quantity_mode_active()) {
            $class_active .= 'quantity-group-btn';

            if (besa_is_quantity_field_archive()) {
                $class_active .= ' active';
            }
        } else {
            $class_active .= 'woo-swatches-pro-btn';
        }

        echo '<div class="'.esc_attr($class_active).'">';

        if (besa_is_quantity_field_archive() && besa_woocommerce_quantity_mode_active()) {
            besa_quantity_swatches_pro_field_archive();
        }

        woocommerce_template_loop_add_to_cart();
        echo '</div>';
    }
}
