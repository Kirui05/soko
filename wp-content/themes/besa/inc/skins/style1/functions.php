<?php

if ( !function_exists('besa_tbay_list_theme_icons') ) {
	function besa_tbay_list_theme_icons() {

		$theme_icons = array(
			'icon_cart'						=> 'tb-icon tb-icon-cart-plus',
			'icon_cart_mobile'				=> 'tb-icon tb-icon-cart',
			'icon_wishlist'					=> 'tb-icon tb-icon-heart',
			'icon_wishlist_mb'				=> 'tb-icon tb-icon-heart',
			'icon_zt_plus'						=> 'tb-icon tb-icon-zt-plus',
			'icon_quick_view'				=> 'tb-icon tb-icon-eye',
			'icon_slick_prev'				=> 'icon-arrow-left icons',
			'icon_slick_next'				=> 'icon-arrow-right icons',
			'icon_shop_grid'				=> 'fas fa-th-large',
			'icon_shop_list'				=> 'fas fa-th-list',
			'icon_remove'					=> 'tb-icon tb-icon-trash',
			'icon_post_aside'				=> 'tb-icon tb-icon-document',
			'icon_post_audio'				=> 'tb-icon tb-icon-music-note2',
			'icon_post_gallery'				=> 'tb-icon tb-icon-pictures',
			'icon_post_image'				=> 'tb-icon tb-icon-picture2',
			'icon_post_video'				=> 'tb-icon tb-icon-clapboard-play',
			'icon_post_default'				=> 'tb-icon tb-icon-pushpin',
			'icon_date_blog'			    => 'tb-icon tb-icon-calendar-31',		
			'icon_read_more'				=> 'tb-icon tb-icon-plus',
			'icon_back_to_top'				=> 'tb-icon tb-icon-chevron-up',
			'icon_close'					=> 'tb-icon tb-icon-cross2',
			'icon_close_2'					=> 'tb-icon tb-icon-cross',
			'icon_filter'					=> 'tb-icon tb-icon-equalizer',
			'icon_minus'					=> 'tb-icon tb-icon-minus',
			'icon_plus'						=> 'tb-icon tb-icon-plus',
			'icon_attr_open'				=> 'tb-icon tb-icon-angle-right',
		);

		return apply_filters( 'besa_tbay_list_theme_icons', $theme_icons );
	}
}

if ( !function_exists('besa_get_icon') ) {
	function besa_get_icon($icon_name) {
		$social_icons = besa_tbay_list_theme_icons();

		switch ($icon_name) {
			case $icon_name:
				$icon = $social_icons[$icon_name];
				break;
			
			default:
				$icon = '';
				break;
		}

		return $icon;
	}
}