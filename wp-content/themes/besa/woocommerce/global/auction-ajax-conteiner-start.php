<?php
/**
 * Auction ajax container start
 *
 */

 /**
 * auction-ajax-conteiner-start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/auction-ajax-conteiner-start.php.
 *
 * HOWEVER, on occasion Woocommerce Simple Auctions will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://www.wpgenie.org/woocommerce-simple-auctions/documentation/#translating - How can I hide usernames in auction history list?
 * @package woocommerce-simple-auctions\Templates
 * @version 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $product;
$user_id = get_current_user_id();
$class_payed = '';
if ( ($user_id == $product->get_auction_current_bider() && $product->get_auction_closed() == '2' && !$product->get_auction_payed() ) )  {
    $class_payed = ' pay-now';
}

?>
<div class='auction-ajax-change<?php echo esc_attr($class_payed); ?>' >
