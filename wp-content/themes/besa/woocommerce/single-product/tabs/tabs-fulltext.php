<?php
/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
$i = 0;
if ( ! empty( $product_tabs ) ) : ?>

    <div class="woocommerce-tabs tabs-fulltext">
        <div class="tab-content">
        <?php $i = 0; ?>
        <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
            <div class="item-panel" id="tabs-list-<?php echo esc_attr( $key ); ?>">
                <?php call_user_func( $product_tab['callback'], $key, $product_tab ); ?>
            </div>
        <?php $i++; endforeach; ?>
        </div>

        <?php do_action( 'woocommerce_product_after_tabs' ); ?>
    </div>
<?php endif; ?>