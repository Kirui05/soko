<?php 

if( !(defined('BESA_WOOCOMMERCE_ACTIVED') && BESA_WOOCOMMERCE_ACTIVED) || is_user_logged_in() || is_account_page() ) return;



do_action( 'besa_woocommerce_before_customer_login_form' ); 

if ( class_exists('WeDevs_Dokan') && !is_user_logged_in() ) {
    dokan()->scripts->load_form_validate_script();
    wp_enqueue_script( 'dokan-vendor-registration' );
}

?>

<div id="custom-login-wrapper" class="modal fade woocommerce-account" role="dialog">

    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal"><i class="tb-icon tb-icon-cross2"></i></button>
            <div class="modal-body">

                <?php echo do_shortcode('[woocommerce_my_account]'); ?>
        
            </div>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>