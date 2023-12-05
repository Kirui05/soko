<?php
if( !class_exists('YITH_Auctions') || !class_exists('YITH_Auctions_Premium') ) return;
 

if( ! function_exists( 'besa_yith_remove_print_auction_condition' ) ) { 
    function besa_yith_remove_print_auction_condition() {
        $yith_auctions = YITH_Auction_Frontend_Premium::get_instance();
        remove_action( 'yith_wcact_in_to_form_add_to_cart', array( $yith_auctions, 'print_auction_condition' ), 10 );
    }    
    add_action('yith_wcact_before_add_to_cart_form', 'besa_yith_remove_print_auction_condition', 10);
} 

if ( !function_exists('besa_yith_auction_setup_option') ) {
    function besa_yith_auction_setup_option() {
        update_option( 'yith_wcact_settings_tab_auction_show_button','theme' );
        update_option( 'yith_wcact_show_badge_product_page','no' );
    }
    add_action( 'after_setup_theme', 'besa_yith_auction_setup_option', 10 );
}    
 

if( ! function_exists( 'besa_yith_add_print_auction_condition' ) ) { 
    add_action( 'woocommerce_single_product_summary', 'besa_yith_add_print_auction_condition', 5 ); 
    function besa_yith_add_print_auction_condition() { 
        global $product;
        if ( $product && 'auction' == $product->get_type() && 'yes' == get_option( 'yith_wcact_show_item_condition', 'no' ) && $condition = $product->get_item_condition() ) {
            /* translators: %1$s is replaced with the string of item conditions provided by admin */
            ?>
                <div class="yith-wcact-item-condition">
                    <?php echo sprintf( esc_html__( 'Condition: %1$s','besa' ), $condition  )  ?>
                </div>
            <?php
        
        }
    }
}

if( ! function_exists( 'besa_yith_remove_add_button_buy_now' ) ) { 
    function besa_yith_remove_add_button_buy_now() {
        $yith_auctions = YITH_Auction_Frontend_Premium::get_instance();
        remove_action( 'yith_wcact_after_add_button_bid', array( $yith_auctions, 'add_button_buy_now' ), 20 );
    }    
    add_action('yith_wcact_before_add_to_cart_form', 'besa_yith_remove_add_button_buy_now', 10);
} 


if( ! function_exists( 'besa_yith_add_button_buy_now' ) ) { 
    function besa_yith_add_button_buy_now() {
        $yith_auctions = YITH_Auction_Frontend_Premium::get_instance();
        add_action( 'yith_wcact_before_add_button_bid', array( $yith_auctions, 'add_button_buy_now' ), 20 );
    }    
    add_action('yith_wcact_before_form_auction_product', 'besa_yith_add_button_buy_now', 10, 1);
} 


if( ! function_exists( 'besa_yith_auction_change_sticky_menu_bar_class' ) ) { 
    add_filter('besa_sticky_menu_bar_class_auction', 'besa_yith_auction_change_sticky_menu_bar_class', 10, 1);
    function besa_yith_auction_change_sticky_menu_bar_class() {
        return 'yith-auctions';  
    }
}

if( ! function_exists( 'besa_yith_wcact_load_script' ) ) { 
    add_action('yith_wcact_enqueue_fontend_scripts', 'besa_yith_wcact_load_script', 99);
    function besa_yith_wcact_load_script() {
        if( ! is_product() ) {
            if( wp_script_is( 'yith_wcact_frontend_shop_premium' ) ) {
                wp_dequeue_script( 'yith_wcact_frontend_shop_premium' );
            }

            if( ! wp_script_is( 'yith-wcact-frontend-js-premium' )  ) {
                wp_enqueue_script( 'yith-wcact-frontend-js-premium' );
            }
    
            wp_enqueue_style( 'yith-wcact-frontend-css' );
        }
    }
}



if( ! function_exists( 'besa_yith_wcact_load_acution_price_html' ) ) { 
    function besa_yith_wcact_load_acution_price_html( ) {

        if( is_admin() ) {
            return false;
        } else {
            return true;
        }
        
    }  
    add_filter('yith_wcact_load_acution_price_html', 'besa_yith_wcact_load_acution_price_html', 10, 1);
}  