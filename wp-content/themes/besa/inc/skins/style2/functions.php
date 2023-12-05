<?php
if ( !function_exists('besa_tbay_list_theme_icons') ) {
	function besa_tbay_list_theme_icons() {

		$theme_icons = array(
			'icon_cart'						=> 'tb-icon tb-icon-zt-shopping-cart',
			'icon_cart_mobile'				=> 'tb-icon tb-icon-zt-shopping-cart-02',
			'icon_wishlist'					=> 'tb-icon tb-icon-zt-heart-01',
			'icon_wishlist_mb'				=> 'tb-icon tb-icon-zt-heart-02',
			'icon_zt_plus'					=> 'tb-icon tb-icon-zt-plus',
			'icon_quick_view'				=> 'tb-icon tb-icon-zt-expand',
			'icon_slick_prev'				=> 'tb-icon tb-icon-zt-angle-left',
			'icon_slick_next'				=> 'tb-icon tb-icon-zt-angle-right',
			'icon_shop_grid'				=> 'tb-icon tb-icon-zt-th-large',
			'icon_shop_list'				=> 'tb-icon tb-icon-zt-list-alt',
			'icon_remove'					=> 'tb-icon tb-icon-zt-delete',
			'icon_post_aside'				=> 'tb-icon tb-icon-document',
			'icon_post_audio'				=> 'tb-icon tb-icon-zt-customer-service',
			'icon_post_gallery'				=> 'tb-icon tb-icon-zt-appstore',
			'icon_post_image'				=> 'tb-icon tb-icon-zt-picture4',
			'icon_post_video'				=> 'tb-icon tb-icon-zt-play-circle-01',
			'icon_post_default'				=> 'tb-icon tb-icon-zt-pushpin-01',
			'icon_date_blog'			    => 'tb-icon tb-icon-zt-clock-circle',
			'icon_read_more'				=> '',
			'icon_back_to_top'				=> 'tb-icon tb-icon-zt-angle-up',
			'icon_close'					=> 'tb-icon tb-icon-zt-close',
			'icon_close_2'					=> 'tb-icon tb-icon-zt-close',
			'icon_filter'					=> 'tb-icon tb-icon-zt-filter',
			'icon_minus'					=> 'tb-icon tb-icon-zt-minus',
			'icon_plus'						=> 'tb-icon tb-icon-zt-plus',
			'icon_attr_open'				=> 'tb-icon tb-icon-zt-angle-right',
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


if ( !function_exists('besa_change_text_localize_translate') ) {
	function besa_change_text_localize_translate($config) {
		$config['show_all_text'] = esc_html__('View all results', 'besa');
		return $config;
	}
	add_filter('besa_localize_translate','besa_change_text_localize_translate',10,1);
}