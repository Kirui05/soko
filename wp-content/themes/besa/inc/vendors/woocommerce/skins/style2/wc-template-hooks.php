<?php
add_action( 'besa_woo_after_shop_loop_item_caption', 'besa_woocommerce_quantity_mode_group_button', 15);

add_action( 'woocommerce_before_left_main_single_product', 'besa_show_product_view_counter_on_product_page_style2', 6);  