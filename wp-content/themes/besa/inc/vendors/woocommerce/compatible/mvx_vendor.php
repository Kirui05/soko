<?php

if(!class_exists('MVX')) return;

if ( !function_exists( 'besa_mvx_vendor_form_password_repeat' ) ) {
  function besa_mvx_vendor_form_password_repeat() {
    if( !besa_tbay_get_config('show_confirm_password', true) || 'yes' === get_option( 'woocommerce_registration_generate_password' ) )  return;

    ?>
    <div class="mvx-regi-12">
        <label for="reg_password2"><?php _e('Confirm Password', 'besa'); ?> <span class="required">*</span></label>
         <input type="password" class="input-text" name="password2" id="reg_password2" required="required" value="<?php if ( ! empty( $_POST['password2'] ) ) echo esc_attr( $_POST['password2'] ); ?>" />
    </div>
    <?php
  } 
  add_action( 'mvx_vendor_register_form', 'besa_mvx_vendor_form_password_repeat', 0 );
}


if( ! function_exists( 'besa_tbay_regiter_vendor_mvx_popup' ) ) {
    function besa_tbay_regiter_vendor_mvx_popup() {
        if( !mvx_vendor_registration_page_id() ) return;

        $outputs = '<div class="vendor-register">';
        $outputs .= sprintf( __( 'Are you a vendor? <a href="%s">Register here.</a>', 'besa' ), get_permalink(get_option('mvx_product_vendor_registration_page_id')) );
        $outputs .= '</div>';
        echo trim($outputs);
    }
    add_action( 'besa_custom_woocommerce_register_form_end', 'besa_tbay_regiter_vendor_mvx_popup', 5 );
}

if( ! function_exists( 'besa_mvx_woo_remove_product_tabs' ) ) {
    add_filter( 'woocommerce_product_tabs', 'besa_mvx_woo_remove_product_tabs', 98 );
    function besa_mvx_woo_remove_product_tabs( $tabs ) {

        unset( $tabs['questions'] );    

        return $tabs;
    }
}


if(!function_exists('besa_mvx_vendor_name')){
    function besa_mvx_vendor_name() {
        $active = besa_tbay_get_config('show_vendor_name', true);

        if( !$active ) return;

        global $product;
        $product_id = $product->get_id();

        $vendor = get_mvx_product_vendors( $product_id );

        if ( empty( $vendor ) ) {
            return;
        }

        if ( get_mvx_vendor_settings('display_product_seller', 'settings_general') ) {

            $sold_by_text = apply_filters( 'vendor_sold_by_text', esc_html__( 'Sold by:', 'besa' ) );
            ?> 
    
            <div class="sold-by-meta sold-mvx">
                <span class="sold-by-label"><?php echo trim($sold_by_text); ?> </span>
                <a href="<?php echo esc_url( $vendor->permalink ); ?>"><?php echo esc_html( $vendor->user_data->display_name ); ?></a>
            </div>
    
            <?php
        }

    }
    add_filter( 'mvx_sold_by_text_after_products_shop_page', '__return_false' );
    add_action( 'woocommerce_after_shop_loop_item_title', 'besa_mvx_vendor_name', 0 );
    add_action( 'besa_woo_list_caption_right', 'besa_mvx_vendor_name', 15 );
    add_action( 'besa_woo_after_single_rating', 'besa_mvx_vendor_name', 15 );

}

/*Get title My Account in top bar mobile*/
if ( ! function_exists( 'besa_tbay_mvx_get_title_mobile' ) ) {
    function besa_tbay_mvx_get_title_mobile( $title = '') {

        if( besa_woo_is_vendor_page() ) {
            $vendor_id  = get_queried_object()->term_id;
            $vendor     = get_mvx_vendor_by_term($vendor_id);

            $title          = $vendor->page_title;
        }

        return $title;
    }
    add_filter( 'besa_get_filter_title_mobile', 'besa_tbay_mvx_get_title_mobile' );
}

if ( ! function_exists( 'besa_tbay_mvx_description' ) ) {
    function besa_tbay_mvx_description( $description ) {
        global $MVX;

        if( is_tax($MVX->taxonomy->taxonomy_name) ) {
            $vendor_id = get_queried_object()->term_id;
            // Get vendor info
            $vendor = get_mvx_vendor_by_term($vendor_id);

            if( $vendor ){
                $description = $vendor->description;
            }
        }

        return $description;
    }
    add_filter( 'the_content', 'besa_tbay_mvx_description', 10, 1 );
}

/*Fix mvx 3.7*/
if ( !function_exists('besa_mvx_load_default_vendor_store') ) {
    function besa_mvx_load_default_vendor_store() {
        return true;
    }
    add_filter( 'mvx_load_default_vendor_store', 'besa_mvx_load_default_vendor_store', 10, 1 );
}

if ( !function_exists('besa_mvx_store_sidebar_args') ) {
    function besa_mvx_store_sidebar_args() {
        $sidebars = array(
            'name'          => esc_html__( 'Multivendor Marketplace Store Sidebar', 'besa' ),
            'id'            => 'mvx-sidebar-store',
            'description'   => esc_html__( 'Add widgets here to appear in your site.', 'besa' ),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ); 

        return $sidebars;
    }
    add_filter( 'mvx_store_sidebar_args', 'besa_mvx_store_sidebar_args', 10, 1 );
}
/*End fix mvx 3.7*/