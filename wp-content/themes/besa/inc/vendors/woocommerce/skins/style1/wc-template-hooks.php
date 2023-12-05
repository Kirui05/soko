<?php
add_action( 'besa_woo_before_shop_loop_item_caption', 'besa_woocommerce_quantity_mode_group_button', 5);
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

add_action( 'besa_woo_after_single_rating', 'besa_show_product_view_counter_on_product_page_style1', 10);