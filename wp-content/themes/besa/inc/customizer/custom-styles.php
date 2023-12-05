<?php


//convert hex to rgb
if ( !function_exists ('besa_tbay_getbowtied_hex2rgb') ) {
	function besa_tbay_getbowtied_hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
		
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return implode(",", $rgb); // returns the rgb values separated by commas
		//return $rgb; // returns an array with the rgb values
	}
}


if ( !function_exists ('besa_tbay_color_lightens_darkens') ) {
	/**
	 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
	 * @param str $hex Colour as hexadecimal (with or without hash);
	 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
	 * @return str Lightened/Darkend colour as hexadecimal (with hash);
	 */
	function besa_tbay_color_lightens_darkens( $hex, $percent ) {
		
		// validate hex string
		if( empty($hex) ) return $hex;
		
		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';
		
		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}
		
		// convert to decimal and change luminosity
		for ($i = 0; $i < 3; $i++) {
			$dec = substr( $hex, $i*2, 2 );
			$dec = intval( $dec, 16 );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 ); 
			$new_hex .= str_pad( sprintf("%02x", $dec) , 2, 0, STR_PAD_LEFT );
		}	
		
		return $new_hex;
	}
}

if (!function_exists('besa_tbay_check_empty_customize')) {
    function besa_tbay_check_empty_customize($option, $default){
		if( !is_array( $option ) ) {
			if( !empty($option) && $option !== 'Array' ) {
				echo trim( $option );
			} else {
				echo trim( $default );
			}
		} else {
			if( !empty($option['background-color']) ) {
				echo trim( $option['background-color'] );
			} else {
				echo trim( $default );
			}
		} 
	}
}

if ( !function_exists ('besa_tbay_default_theme_primary_color') ) {
	function besa_tbay_default_theme_primary_color() {
		$theme_color = array();

		if ( besa_tbay_get_global_config('dark_mode', false ) ) {
			 
			$theme_color['boby_bg'] 				= '#242424';
			$theme_color['main_color'] 				= '#e6a899';
			$theme_color['main_color_second'] 		= '#e6a899';
			$theme_color['header_mobile_bg'] 		= '#d2664b';
			$theme_color['header_mobile_color'] 	= '#fff';
			$theme_color['bg_buy_now'] 				= '#e6a899';

		} else {

			$theme_color['boby_bg'] 				= '#f5f5f5';
			$theme_color['main_color'] 				= '#fa4f26';
			$theme_color['main_color_second'] 		= '#fcd537';
			$theme_color['header_mobile_bg'] 		= '#fa4f26';
			$theme_color['header_mobile_color'] 	= '#fff';
			$theme_color['bg_buy_now'] 				= '#fcd537';
			
		} 

		return apply_filters( 'besa_get_default_theme_color', $theme_color);
	}
}

if (!function_exists('besa_tbay_theme_primary_color')) {
    function besa_tbay_theme_primary_color()
    {
		$default_color 		= besa_tbay_default_theme_primary_color();
		$boby_bg   = besa_tbay_get_config(('boby_bg'),$default_color['boby_bg']);
		$main_color   = besa_tbay_get_config(('main_color'),$default_color['main_color']);
		$main_color_second   = besa_tbay_get_config(('main_color_second'),$default_color['main_color_second']);
		$header_mobile_bg   = besa_tbay_get_config(('header_mobile_bg'),$default_color['header_mobile_bg']);
		$header_mobile_color   = besa_tbay_get_config(('header_mobile_color'),$default_color['header_mobile_color']);
		$bg_buy_now     = besa_tbay_get_config(('bg_buy_now'),$default_color['bg_buy_now']);

		/*Theme Color*/
		?>
		:root {
			--tb-theme-body: <?php besa_tbay_check_empty_customize( $boby_bg, $default_color['boby_bg'] ); ?>;
			--tb-theme-color: <?php besa_tbay_check_empty_customize( $main_color, $default_color['main_color']) ?>;
			--tb-theme-color-hover: <?php besa_tbay_check_empty_customize( besa_tbay_color_lightens_darkens($main_color, -0.05), besa_tbay_color_lightens_darkens($default_color['main_color'], -0.05) );  ?>;
			--tb-theme-color-hover-2: <?php besa_tbay_check_empty_customize(besa_tbay_color_lightens_darkens($main_color, -0.1),besa_tbay_color_lightens_darkens($default_color['main_color'], -0.1)); ?>;
			--tb-theme-second-color: <?php besa_tbay_check_empty_customize( $main_color_second, $default_color['main_color_second'])?>; 
			--tb-theme-second-color-hover: <?php besa_tbay_check_empty_customize(besa_tbay_color_lightens_darkens($main_color_second, -0.05),besa_tbay_color_lightens_darkens($default_color['main_color_second'], -0.05)); ?>;
			--tb-theme-second-color-hover-2: <?php besa_tbay_check_empty_customize(besa_tbay_color_lightens_darkens($main_color_second, -0.1),besa_tbay_color_lightens_darkens($default_color['main_color_second'], -0.1)); ?>;
			--tb-header-mobile-bg: <?php besa_tbay_check_empty_customize($header_mobile_bg, $default_color['header_mobile_bg']) ?>;
			--tb-header-mobile-color: <?php besa_tbay_check_empty_customize($header_mobile_color, $default_color['header_mobile_color'] )?>;
			--tb-bg-buy-now: <?php besa_tbay_check_empty_customize($bg_buy_now, $default_color['bg_buy_now'] )?>;
			--tb-bg-buy-now-hover: <?php besa_tbay_check_empty_customize( besa_tbay_color_lightens_darkens($bg_buy_now, -0.1), besa_tbay_color_lightens_darkens($default_color['bg_buy_now'], -0.1) );  ?>;
		} 
		<?php
    }
}

if ( !function_exists ('besa_tbay_custom_styles') ) {
	function besa_tbay_custom_styles() {
		ob_start();	

		besa_tbay_theme_primary_color();

		if (defined('TBAY_ELEMENTOR_ACTIVED')) {
			$logo_img_width        		= besa_tbay_get_config( 'logo_img_width' );
			$logo_padding        		= besa_tbay_get_config( 'logo_padding' );	

			$logo_img_width_mobile 		= besa_tbay_get_config( 'logo_img_width_mobile' );
			$logo_mobile_padding 		= besa_tbay_get_config( 'logo_mobile_padding' );

			$custom_css 			= besa_tbay_get_config( 'custom_css' );
			$css_desktop 			= besa_tbay_get_config( 'css_desktop' );
			$css_tablet 			= besa_tbay_get_config( 'css_tablet' );
			$css_wide_mobile 		= besa_tbay_get_config( 'css_wide_mobile' );
			$css_mobile         	= besa_tbay_get_config( 'css_mobile' );

			$show_typography        = (bool) besa_tbay_get_config( 'show_typography', false );

			
			if( $show_typography ) {
				/* Typography */
				/* Main Font */
				$font_source = besa_tbay_get_config('font_source');
				$main_google_font_face = besa_tbay_get_config('main_google_font_face');
				$main_custom_font_face = besa_tbay_get_config('main_custom_font_face');

				if ( empty( besa_tbay_get_config('main_font')['font-family']) && $font_source  == "1") {
					$primary_font = 'Open Sans';
				} elseif ($font_source  == "3" && $main_custom_font_face) {
					$primary_font = $main_custom_font_face;
				} elseif ($font_source  == "2" && $main_google_font_face) {
					$primary_font = $main_google_font_face;
				} elseif ($font_source  == "1") {
					$primary_font 			= besa_tbay_get_config('main_font')['font-family'];
				} 
				?>
					:root {
						--tb-text-primary-font: <?php echo trim($primary_font) ?>
					}
				<?php
			} else {
				?>
					:root {
						--tb-text-primary-font: 'Open Sans' 
					}
				<?php	
			}
				
				

			?>
			
			/* Theme Options Styles */	

				/* Custom Color (skin) */ 

				<?php if ( $logo_img_width != "" ) : ?>
					.site-header .logo img {
						max-width: <?php echo esc_html( $logo_img_width ); ?>px;
					} 
				<?php endif; ?>

				<?php if ( $logo_padding != "" ) : ?>
					.site-header .logo img {

						<?php if( !empty($logo_padding['padding-top'] ) ) : ?>
							padding-top: <?php echo esc_html( $logo_padding['padding-top'] ); ?>;
						<?php endif; ?>

						<?php if( !empty($logo_padding['padding-right'] ) ) : ?>
							padding-right: <?php echo esc_html( $logo_padding['padding-right'] ); ?>;
						<?php endif; ?>
						
						<?php if( !empty($logo_padding['padding-bottom'] ) ) : ?>
							padding-bottom: <?php echo esc_html( $logo_padding['padding-bottom'] ); ?>;
						<?php endif; ?>

						<?php if( !empty($logo_padding['padding-left'] ) ) : ?>
							padding-left: <?php echo esc_html( $logo_padding['padding-left'] ); ?>;
						<?php endif; ?>

					}
				<?php endif; ?> 


				@media (max-width: 1199px) {

					<?php if ( $logo_img_width_mobile != "" ) : ?>
						/* Limit logo image height for mobile according to mobile header height */
						.mobile-logo a img {
							max-width: <?php echo esc_html( $logo_img_width_mobile ); ?>px;
						}     
					<?php endif; ?>       

					<?php if ( $logo_mobile_padding['padding-top'] != "" || $logo_mobile_padding['padding-right'] || $logo_mobile_padding['padding-bottom'] || $logo_mobile_padding['padding-left']  ) : ?>
						.mobile-logo a img {

							<?php if( !empty($logo_mobile_padding['padding-top'] ) ) : ?>
								padding-top: <?php echo esc_html( $logo_mobile_padding['padding-top'] ); ?>;
							<?php endif; ?>

							<?php if( !empty($logo_mobile_padding['padding-right'] ) ) : ?>
								padding-right: <?php echo esc_html( $logo_mobile_padding['padding-right'] ); ?>;
							<?php endif; ?>

							<?php if( !empty($logo_mobile_padding['padding-bottom'] ) ) : ?>
								padding-bottom: <?php echo esc_html( $logo_mobile_padding['padding-bottom'] ); ?>;
							<?php endif; ?>

							<?php if( !empty($logo_mobile_padding['padding-left'] ) ) : ?>
								padding-left: <?php echo esc_html( $logo_mobile_padding['padding-left'] ); ?>;
							<?php endif; ?>
						
						}
					<?php endif; ?>
				}

				/* Custom CSS */
				<?php 
				if( $custom_css != '' ) {
					echo trim($custom_css);
				}
				if( $css_desktop != '' ) {
					echo '@media (min-width: 1024px) { ' . ($css_desktop) . ' }'; 
				}
				if( $css_tablet != '' ) {
					echo '@media (min-width: 768px) and (max-width: 1023px) {' . ($css_tablet) . ' }'; 
				}
				if( $css_wide_mobile != '' ) {
					echo '@media (min-width: 481px) and (max-width: 767px) { ' . ($css_wide_mobile) . ' }'; 
				}
				if( $css_mobile != '' ) {
					echo '@media (max-width: 480px) { ' . ($css_mobile) . ' }'; 
				}
				?>


		<?php
		}
		
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			} 
		}

		$custom_css = implode($new_lines);

		wp_enqueue_style( 'besa-style', BESA_THEME_DIR . '/style.css', array(), '1.0' );

		wp_add_inline_style( 'besa-style', $custom_css );

	}
}

add_action( 'wp_enqueue_scripts', 'besa_tbay_custom_styles', 600 ); 