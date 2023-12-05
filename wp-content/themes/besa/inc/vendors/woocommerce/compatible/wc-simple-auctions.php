<?php
if( !class_exists('WooCommerce_simple_auction') ) return;

if ( ! is_admin() || defined('DOING_AJAX') ) {
    // Countdown auctions - layout product
    add_action( 'besa_woo_before_shop_loop_item_caption', 'woocommerce_auction_countdown', 1);
    add_action( 'besa_woo_before_shop_list_caption', 'woocommerce_auction_countdown', 1);


    /**Single Product**/
    add_action( 'woocommerce_single_product_summary', 'woocommerce_auction_condition', 8 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_auction_reserve', 15 );    
    add_action( 'woocommerce_before_left_main_single_product', 'woocommerce_auction_countdown', 15 );
    add_action( 'woocommerce_before_left_main_single_product', 'woocommerce_auction_dates', 15 );
}

if( ! function_exists( 'besa_change_hook_single_product' ) ) { 
    add_action('woocommerce_before_single_product', 'besa_change_hook_single_product', 10);
    function besa_change_hook_single_product() {
        global $product;
        if ( ! $product->is_purchasable() OR ! $product->is_sold_individually() OR ! $product->is_in_stock() OR $product->get_type() !== 'auction' OR $product->is_closed()) return;

        add_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_auction_ajax_conteiner_start', 5 );
        add_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_auction_max_bid', 6 );
        add_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_auction_bid_form', 6 );
        add_action( 'woocommerce_after_add_to_cart_form', 'woocommerce_auction_ajax_conteiner_end', 89 );

        add_action('woocommerce_before_bid_form', 'besa_remove_watchlist_link', 10);
        add_action('woocommerce_before_add_to_cart_form', 'besa_add_watchlist_link', 10);
        add_action('woocommerce_before_single_product_summary', 'besa_change_remove_hook_auctions_single_product', 10);
    }
}



if( ! function_exists( 'besa_remove_watchlist_link' ) ) { 
    function besa_remove_watchlist_link() {
        global $woocommerce_auctions;
        remove_action( 'woocommerce_after_bid_form', array( $woocommerce_auctions, 'add_watchlist_link' ), 10 );
    }
}

if( ! function_exists( 'besa_add_watchlist_link' ) ) { 
    function besa_add_watchlist_link() {
        global $woocommerce_auctions;
        add_action( 'woocommerce_after_add_to_cart_form', array( $woocommerce_auctions, 'add_watchlist_link' ), 88 );
    }
}

if( ! function_exists( 'besa_change_remove_hook_auctions_single_product' ) ) { 
    function besa_change_remove_hook_auctions_single_product() {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_ajax_conteiner_start', 21 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_max_bid', 25 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_bid_form', 25 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_ajax_conteiner_end', 27 );
    }
}

if( ! function_exists( 'besa_change_tabs_auctions_history' ) ) { 
    function besa_change_tabs_auctions_history( $tabs ) {

        if( empty( $tabs['simle_auction_history'] ) ) return $tabs;

        $tabs['simle_auction_history']['priority'] = 5;
    
        return $tabs;
        
    }
    add_filter( 'woocommerce_product_tabs', 'besa_change_tabs_auctions_history', 20 );
}

if( ! function_exists( 'besa_remove_woocommerce_auction_condition_single' ) ) { 
    function besa_remove_woocommerce_auction_condition_single() {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_condition', 23 ); 
    }
    add_filter( 'woocommerce_single_product_summary', 'besa_remove_woocommerce_auction_condition_single', 20 );
}

if( ! function_exists( 'besa_remove_woocommerce_auction_reserve' ) ) { 
    function besa_remove_woocommerce_auction_reserve() {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_reserve', 25 ); 
    }
    add_filter( 'woocommerce_single_product_summary', 'besa_remove_woocommerce_auction_reserve', 20 );
}

if( ! function_exists( 'besa_remove_woocommerce_auction_wrapper_countdown_date' ) ) { 
    function besa_remove_woocommerce_auction_wrapper_countdown_date() {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_countdown', 24 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_dates', 24 );
    }
    add_filter( 'woocommerce_single_product_summary', 'besa_remove_woocommerce_auction_wrapper_countdown_date', 20 );
}

if( ! function_exists( 'besa_woo_simple_auction_change_sticky_menu_bar_class' ) ) { 
    add_filter('besa_sticky_menu_bar_class_auction', 'besa_woo_simple_auction_change_sticky_menu_bar_class', 10);
    function besa_woo_simple_auction_change_sticky_menu_bar_class() {
        return 'woo-simple-auction';
    }
} 

if( ! function_exists( 'besa_woo_simple_auction_breadcrumb_show_product_title' ) ) { 
    add_filter('besa_breadcrumb_show_product_title', 'besa_woo_simple_auction_breadcrumb_show_product_title', 10);
    function besa_woo_simple_auction_breadcrumb_show_product_title() {
        return true;
    }
} 