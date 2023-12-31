<?php
/**
 * Auction history tab
 * 
 */

/**
 * auction-history.php
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/auction-history.php.
 *
 * HOWEVER, on occasion Woocommerce Simple Auctions will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://www.wpgenie.org/woocommerce-simple-auctions/documentation/#translating - How can I hide usernames in auction history list?
 * @package woocommerce-simple-auctions\Templates\single-product\tabs\
 * @version 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $post, $product;
$auction_history = apply_filters('woocommerce__auction_history_data', $product->auction_history());
$count_bid = count($auction_history); 

$heading = esc_html( apply_filters('woocommerce_auction_history_heading', esc_html__( 'Auction History', 'besa' ) ) );
$datetimeformat = get_option('date_format').' '.get_option('time_format');
$heading = $heading.'('.$count_bid.')';
?>

<h2><?php echo trim($heading); ?></h2> 

<?php if(($product->is_closed() === TRUE ) and ($product->is_started() === TRUE )) : ?>
    
	<p><?php esc_html_e('Auction has finished', 'besa') ?></p>

	<?php if ($product->get_auction_fail_reason() == '1'){

		 esc_html_e('Auction failed because there were no bids', 'besa');

	} elseif($product->get_auction_fail_reason() == '2'){

		esc_html_e('Auction failed because item did not make it to reserve price', 'besa');
	}
	
	if ( $product->get_auction_closed() == '3' ){ ?>

		<p><?php esc_html_e('Product sold for buy now price', 'besa') ?>: <span><?php echo wp_kses ( wc_price( $product->get_regular_price() ), true ); ?></span></p>

	<?php } elseif ( $product->get_auction_current_bider()){ ?>

		<p><?php esc_html_e('Highest bidder was', 'besa') ?>: <span><?php echo apply_filters( 'woocommerce_simple_auctions_displayname', get_userdata( $product->get_auction_current_bider())->display_name, $product ); ?></span></p>

	<?php } ?>
						
<?php endif; ?>	
        
<table id="auction-history-table-<?php echo esc_attr( $product->get_id() ); ?>" class="auction-history-table">
    <?php 
        
        $auction_history = apply_filters('woocommerce__auction_history_data', $product->auction_history());
                
        if ( !empty($auction_history) ): ?>

        <thead>
            <tr>
                <th><?php esc_html_e('Date', 'besa') ?></th>
                <th><?php esc_html_e('Bid', 'besa') ?></th>
                <th><?php esc_html_e('User', 'besa') ?></th>
                <th><?php esc_html_e('Auto', 'besa') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( $product->is_sealed() ){
            
                echo "<tr>";
                echo "<td colspan='4'  class='sealed'>".esc_html__('This auction is sealed. Upon auction finish auction history and winner will be available to the public.', 'besa')."</td>";
                echo "</tr>"; 
 
        } else {

            foreach ($auction_history as $history_value) {
                echo "<tr>";
                echo "<td class='date'>".mysql2date($datetimeformat ,$history_value->date)."</td>";
                echo "<td class='bid'>".wc_price($history_value->bid)."</td>";
                echo "<td class='username'>". apply_filters( 'woocommerce_simple_auctions_displayname', preg_replace("/(?!^).(?!$)/", "*", get_userdata($history_value->userid)->display_name), $product ) . "</td>";
                if ($history_value->proxy == 1)
                    echo " <td class='proxy'>".__('Auto', 'besa')."</td>";
                else 
                    echo " <td class='proxy'></td>";
                echo "</tr>";
            }
       	}?> 
        </tbody>

    <?php endif;?>
        
	<tr class="start">
        <?php 

        if ($product->is_started() === TRUE ){

            echo '<td class="date">'.esc_html( mysql2date( $datetimeformat, $product->get_auction_start_time() ) ).'</td>'; 
            echo '<td colspan="3" class="started">';
            echo apply_filters('auction_history_started_text', esc_html__( 'Auction started', 'besa' ), $product);
            echo '</td>';

        } else {

                echo '<td  class="date">'.esc_html( mysql2date( $datetimeformat, $product->get_auction_start_time() ) ).'</td>'; 
                echo '<td colspan="3"  class="starting">';
                echo apply_filters('auction_history_starting_text', esc_html__( 'Auction starting', 'besa' ), $product);
                echo '</td>' ;
        }
        ?>
	</tr>
</table>