<?php if ( ! defined('BESA_THEME_DIR')) exit('No direct script access allowed');

if ( ! function_exists( 'besa_tbay_body_classes' ) ) {
	function besa_tbay_body_classes( $classes ) {
		global $post;
		if ( is_page() && is_object($post) ) {
			$class = get_post_meta( $post->ID, 'tbay_page_extra_class', true );
			if ( !empty($class) ) {
				$classes[] = trim($class);
			}
		}
		if ( besa_tbay_get_config('preload') ) {
			$classes[] = 'tbay-body-loader';
		}		

		if ( besa_tbay_is_home_page() ) {
			$classes[] = 'tbay-homepage-demo';
		}

		if ( besa_tbay_get_config('always_display_logo') ) {
			$classes[] = 'tbay-always-display-logo';
		}


	  	if( !defined('TBAY_ELEMENTOR_ACTIVED') ) {
	  	 	$classes[] = 'tbay-body-default';
	  	}

		if( class_exists('YITH_Auctions') || class_exists('YITH_Auctions_Premium') ) {
			$classes[] = 'tbay-yith-auctions';
	   	}

		if( besa_tbay_get_config('dark_mode', false) || ( isset($_GET['darkmode']) && $_GET['darkmode'] == 'on' ) ) {
			$classes[] = 'tbay-dark-mode-active';
		}

		return $classes;
	}
	add_filter( 'body_class', 'besa_tbay_body_classes' );
}


if ( ! function_exists( 'besa_tbay_body_home_classes' ) ) {
	function besa_tbay_body_home_classes( $classes ) {
		global $post;
		if ( is_page() && is_object($post) ) {
			$slug = get_queried_object()->post_name;
			if ( !empty($slug) ) {
				$classes[] = trim($slug);
			}
		} 

		if( is_front_page() ) {
			$class = 'tbay-home';
			if ( !empty($class) ) {
				$classes[] = trim($class);
			}
		}

		return $classes;
	}
	add_filter( 'body_class', 'besa_tbay_body_home_classes' );
}

if ( ! function_exists( 'besa_tbay_get_shortcode_regex' ) ) {
	function besa_tbay_get_shortcode_regex( $tagregexp = '' ) {
		// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
		// Also, see shortcode_unautop() and shortcode.js.
		return
			'\\['                                // Opening bracket
			. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "($tagregexp)"                     // 2: Shortcode name
			. '(?![\\w-])'                       // Not followed by word character or hyphen
			. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
			. '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			. '(?:'
			. '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			. '[^\\]\\/]*'               // Not a closing bracket or forward slash
			. ')*?'
			. ')'
			. '(?:'
			. '(\\/)'                        // 4: Self closing tag ...
			. '\\]'                          // ... and closing bracket
			. '|'
			. '\\]'                          // Closing bracket
			. '(?:'
			. '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			. '[^\\[]*+'             // Not an opening bracket
			. '(?:'
			. '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			. '[^\\[]*+'         // Not an opening bracket
			. ')*+'
			. ')'
			. '\\[\\/\\2\\]'             // Closing shortcode tag
			. ')?'
			. ')'
			. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}
}

if ( ! function_exists( 'besa_tbay_tagregexp' ) ) {
	function besa_tbay_tagregexp() {
		return apply_filters( 'besa_tbay_custom_tagregexp', 'video|audio|playlist|video-playlist|embed|besa_tbay_media' );
	}
}


if( ! function_exists( 'besa_tbay_text_line')) {
	function besa_tbay_text_line( $str ) {
		return trim(preg_replace("/('|\"|\r?\n)/", '', $str)); 
	}
}

if ( !function_exists('besa_tbay_get_header_layouts') ) {
	function besa_tbay_get_header_layouts() {
		$headers = array( 'header_default' => esc_html__('Default', 'besa'));
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'tbay_header',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$headers[$post->post_name] = $post->post_title;
		}
		return $headers;
	}
}

if ( !function_exists('besa_tbay_get_header_layout') ) {
	function besa_tbay_get_header_layout() {
		if ( is_page() ) {
			global $post; 
			$header = '';
			if ( is_object($post) && isset($post->ID) ) {
				$header = get_post_meta( $post->ID, 'tbay_page_header_type', true );
				if ( $header == 'global' ||  $header == '') {
					return besa_tbay_get_config('header_type', 'header_default');
				}
			}
			return $header;
		} else if( class_exists( 'WooCommerce' ) && is_shop() ) {
			return besa_tbay_woo_get_header_layout( wc_get_page_id( 'shop' ) );
		} else if( class_exists( 'WooCommerce' ) && is_cart() ) {
			return besa_tbay_woo_get_header_layout( wc_get_page_id( 'cart' ) );
		} else if( class_exists( 'WooCommerce' ) && is_checkout() ) {
			return besa_tbay_woo_get_header_layout( wc_get_page_id( 'checkout' ) );
		} 

		return besa_tbay_get_config('header_type', 'header_default');
	}
	add_filter('besa_tbay_get_header_layout', 'besa_tbay_get_header_layout');
}

if ( !function_exists('besa_tbay_woo_get_header_layout') ) {
	function besa_tbay_woo_get_header_layout( $page_id ) {
		$header = get_post_meta( $page_id, 'tbay_page_header_type', true );

		if ( $header == 'global' ||  $header == '') {
			return besa_tbay_get_config('header_type', 'header_default');
		} else {
			return $header;
		}
	}
}

if ( !function_exists('besa_tbay_get_footer_layouts') ) {
	function besa_tbay_get_footer_layouts() {
		$footers = array( 'footer_default' => esc_html__('Default', 'besa'));
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'tbay_footer',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$footers[$post->post_name] = $post->post_title;
		}
		return $footers;
	}
}

if ( !function_exists('besa_tbay_get_footer_layout') ) {
	function besa_tbay_get_footer_layout() {
		if ( is_page() ) {
			global $post;
			$footer = '';
			if ( is_object($post) && isset($post->ID) ) {
				$footer = get_post_meta( $post->ID, 'tbay_page_footer_type', true );
				if ( $footer == 'global' ||  $footer == '') {
					return besa_tbay_get_config('footer_type', 'footer_default');
				}
			}
			return $footer;
		} else if( class_exists( 'WooCommerce' ) && is_shop() ) {
			return besa_tbay_woo_get_footer_layout( wc_get_page_id( 'shop' ) );
		} else if( class_exists( 'WooCommerce' ) && is_cart() ) {
			return besa_tbay_woo_get_footer_layout( wc_get_page_id( 'cart' ) );
		} else if( class_exists( 'WooCommerce' ) && is_checkout() ) {
			return besa_tbay_woo_get_footer_layout( wc_get_page_id( 'checkout' ) );
		}

		return besa_tbay_get_config('footer_type', 'footer_default');
	}
	add_filter('besa_tbay_get_footer_layout', 'besa_tbay_get_footer_layout');
}

if ( !function_exists('besa_tbay_woo_get_footer_layout') ) {
	function besa_tbay_woo_get_footer_layout( $page_id ) {
		$footer = get_post_meta( $page_id, 'tbay_page_footer_type', true );

		if ( $footer == 'global' ||  $footer == '') {
			return besa_tbay_get_config('footer_type', 'footer_default');
		} else {
			return $footer;
		}
	}
}

if (!function_exists('besa_register_widget_template_elementor')) {
    function besa_register_widget_template_elementor($widgets)
    {
        array_push($widgets, 'Tbay_Widget_Template_Elementor');
        return $widgets;
    } 
    add_filter('tbay_elementor_register_widgets_theme', 'besa_register_widget_template_elementor', 10, 1);
}

if ( !function_exists('besa_tbay_blog_content_class') ) {
	function besa_tbay_blog_content_class( $class ) {
		$page = 'archive';
		if ( is_singular( 'post' ) ) {
            $page = 'single';
        }
		if ( besa_tbay_get_config('blog_'.$page.'_fullwidth') ) {
			return 'container-fluid';
		}
		return $class;
	}
}
add_filter( 'besa_tbay_blog_content_class', 'besa_tbay_blog_content_class', 1 , 1  );

// layout class for woo page
if ( !function_exists('besa_tbay_post_content_class') ) {
    function besa_tbay_post_content_class( $class ) {
        $page = 'archive';
        if ( is_singular( 'post' ) ) {
            $page = 'single';

            if( !isset($_GET['blog_'.$page.'_layout']) ) {
                $class .= ' '.besa_tbay_get_config('blog_'.$page.'_layout');
            }  else {
                $class .= ' '.$_GET['blog_'.$page.'_layout'];
            }

        } else {

            if( !isset($_GET['blog_'.$page.'_layout']) ) {
                $class .= ' '.besa_tbay_get_config('blog_'.$page.'_layout');
            }  else {
                $class .= ' '.$_GET['blog_'.$page.'_layout'];
            }

        }
        return $class;
    }
}
add_filter( 'besa_tbay_post_content_class', 'besa_tbay_post_content_class' );


if ( !function_exists('besa_tbay_get_page_layout_configs') ) {
	function besa_tbay_get_page_layout_configs() {
		global $post;
		if( isset($post->ID) ) {
			$left = get_post_meta( $post->ID, 'tbay_page_left_sidebar', true );
			$right = get_post_meta( $post->ID, 'tbay_page_right_sidebar', true );

			switch ( get_post_meta( $post->ID, 'tbay_page_layout', true ) ) {
				case 'left-main':
					$configs['sidebar'] = array( 'id' => $left, 'class' => 'col-12 col-lg-3'  );
					$configs['main'] 	= array( 'class' => 'col-12 col-lg-9' );
					break;
				case 'main-right':
					$configs['sidebar'] = array( 'id' => $right,  'class' => 'col-12 col-lg-3' ); 
					$configs['main'] 	= array( 'class' => 'col-12 col-lg-9' );
					break;
				case 'main':
					$configs['main'] = array( 'class' => 'col-12' );
					break;
				default:
					$configs['main'] = array( 'class' => 'col-12' );
					break;
			}

			return $configs; 
		}
	}
}

if ( ! function_exists( 'besa_tbay_get_first_url_from_string' ) ) {
	function besa_tbay_get_first_url_from_string( $string ) {
		$pattern = "/^\b(?:(?:https?|ftp):\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
		preg_match( $pattern, $string, $link );

		return ( ! empty( $link[0] ) ) ? $link[0] : false;
	}
}

/*Check in home page*/
if ( !function_exists('besa_tbay_is_home_page') ) {
	function besa_tbay_is_home_page() {
		$is_home = false;

		if( is_home() || is_front_page() || is_page( 'home-1' ) || is_page( 'home-2' ) || is_page( 'home-3' ) || is_page( 'home-4' ) || is_page( 'home-5' ) || is_page( 'home-6' ) || is_page( 'home-7' )) {
			$is_home = true;
		}

		return $is_home;
	}
}

if ( !function_exists( 'besa_tbay_get_link_attributes' ) ) {
	function besa_tbay_get_link_attributes( $string ) {
		preg_match( '/<a href="(.*?)">/i', $string, $atts );

		return ( ! empty( $atts[1] ) ) ? $atts[1] : '';
	}
}

if ( !function_exists( 'besa_tbay_post_media' ) ) {
	function besa_tbay_post_media( $content ) {
		$is_video = ( get_post_format() == 'video' ) ? true : false;
		$media = besa_tbay_get_first_url_from_string( $content );
		if ( ! empty( $media ) ) {
			global $wp_embed;
			$content = do_shortcode( $wp_embed->run_shortcode( '[embed]' . $media . '[/embed]' ) );
		} else {
			$pattern = besa_tbay_get_shortcode_regex( besa_tbay_tagregexp() );
			preg_match( '/' . $pattern . '/s', $content, $media );
			if ( ! empty( $media[2] ) ) {
				if ( $media[2] == 'embed' ) {
					global $wp_embed;
					$content = do_shortcode( $wp_embed->run_shortcode( $media[0] ) );
				} else {
					$content = do_shortcode( $media[0] );
				}
			}
		}
		if ( ! empty( $media ) ) {
			$output = '<div class="entry-media">';
			$output .= ( $is_video ) ? '<div class="pro-fluid"><div class="pro-fluid-inner">' : '';
			$output .= $content;
			$output .= ( $is_video ) ? '</div></div>' : '';
			$output .= '</div>';

			return $output;
		}

		return false;
	}
}

if ( !function_exists( 'besa_tbay_post_gallery' ) ) {
	function besa_tbay_post_gallery( $content ) {
		$pattern = besa_tbay_get_shortcode_regex( 'gallery' );
		preg_match( '/' . $pattern . '/s', $content, $media );
		if ( ! empty( $media[2] )  ) {
			return '<div class="entry-gallery">' . do_shortcode( $media[0] ) . '<hr class="pro-clear" /></div>';
		}

		return false;
	}
}

if ( !function_exists( 'besa_tbay_random_key' ) ) {
    function besa_tbay_random_key($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $return = '';
        for ($i = 0; $i < $length; $i++) {
            $return .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $return;
    }
}

if ( !function_exists('besa_tbay_substring') ) {
    function besa_tbay_substring($string, $limit, $afterlimit = '[...]') {
        if ( empty($string) ) {
        	return $string;
        }
       	$string = explode(' ', strip_tags( $string ), $limit);

        if (count($string) >= $limit) {
            array_pop($string);
            $string = implode(" ", $string) .' '. $afterlimit;
        } else {
            $string = implode(" ", $string);
        }
        $string = preg_replace('`[[^]]*]`','',$string);
        return strip_shortcodes( $string );
    }
}

if ( !function_exists('besa_tbay_subschars') ) {
    function besa_tbay_subschars($string, $limit, $afterlimit='...'){

	    if(strlen($string) > $limit){
	        $string = substr($string, 0, $limit);
	    }else{
	        $afterlimit = '';
	    }
	    return $string . $afterlimit;
	}
}


/*Besa get template parts*/
if ( !function_exists('besa_tbay_get_page_templates_parts') ) {
	function besa_tbay_get_page_templates_parts($slug = 'logo', $name = null) {
		return get_template_part( 'page-templates/parts/'.$slug.'',$name);
	}
}

/*testimonials*/
if ( !function_exists('besa_tbay_get_testimonials_layouts') ) {
	function besa_tbay_get_testimonials_layouts() {
		$testimonials = array();
		$files = glob( get_template_directory() . '/vc_templates/testimonial/testimonial.php' );
	    if ( !empty( $files ) ) {
	        foreach ( $files as $file ) {
	        	$testi = str_replace( "testimonial", '', str_replace( '.php', '', basename($file) ) );
	            $testimonials[$testi] = $testi;
	        }
	    }

		return $testimonials;
	}
}

/*Blog*/
if ( !function_exists('besa_tbay_get_blog_layouts') ) {
	function besa_tbay_get_blog_layouts() {
		$blogs = array(
			esc_html__('Grid', 'besa') => 'grid',
			esc_html__('Vertical', 'besa') => 'vertical',
		);
		$files = glob( get_template_directory() . '/vc_templates/post/carousel/_single_*.php' );
	    if ( !empty( $files ) ) {
	        foreach ( $files as $file ) {
	        	$str = str_replace( "_single_", '', str_replace( '.php', '', basename($file) ) );
	            $blogs[$str] = $str;
	        }
	    }

		return $blogs;
	}
}

// Number of blog per row
if ( !function_exists('besa_tbay_blog_loop_columns') ) {
    function besa_tbay_blog_loop_columns($number) {

    		$sidebar_configs = besa_tbay_get_blog_layout_configs();

    		$columns 	= besa_tbay_get_config('blog_columns');

        if( isset($_GET['blog_columns']) && is_numeric($_GET['blog_columns']) ) {
            $value = $_GET['blog_columns']; 
        } elseif( empty($columns) && isset($sidebar_configs['columns']) ) {
    			$value = 	$sidebar_configs['columns']; 
    		} else {
          	$value = $columns;          
        }

        if ( in_array( $value, array(1, 2, 3, 4, 5, 6) ) ) {
            $number = $value;
        }
        return $number;
    }
}
add_filter( 'loop_blog_columns', 'besa_tbay_blog_loop_columns' );

/*Check style blog image full*/
if ( !function_exists( 'besa_tbay_blog_image_sizes_full' ) ) {
    function besa_tbay_blog_image_sizes_full() {
    	$style = false;
    	$sidebar_configs = besa_tbay_get_blog_layout_configs();

       	if ( !is_singular( 'post' ) ) {
       		if( isset($sidebar_configs['image_sizes']) && $sidebar_configs['image_sizes'] == 'full') :
       			$style = true;
       		endif;
        }

        return  $style;

    }
}


// Number of post per page
if ( !function_exists('besa_tbay_loop_post_per_page') ) {
    function besa_tbay_loop_post_per_page($number) {

        if( isset($_GET['posts_per_page']) && is_numeric($_GET['posts_per_page']) ) {
            $value = $_GET['posts_per_page']; 
        } else {
            $value = get_option( 'posts_per_page' );       
        }

        if ( is_numeric( $value ) && $value ) {
            $number = absint( $value );
        }
        
        return $number;
    }
  add_filter( 'loop_post_per_page', 'besa_tbay_loop_post_per_page' );
}

if ( !function_exists('besa_tbay_posts_per_page') ) {
	function besa_tbay_posts_per_page( $wp_query ){

			if ( is_admin() || ! $wp_query->is_main_query() )
	        return;

			$value = apply_filters( 'loop_post_per_page', 6 );

		 	if( isset($value) && is_category() )
		    $wp_query->query_vars['posts_per_page'] = $value;
		 	return $wp_query;
	}
	add_action( 'pre_get_posts', 'besa_tbay_posts_per_page' );
}

if ( !function_exists('besa_tbay_share_js') ) {
	function besa_tbay_share_js() {
		  if( !besa_tbay_get_config('enable_code_share',false) || besa_tbay_get_config('select_share_type') == 'custom' ) return;
		 if ( is_single() ) {
		 	echo besa_tbay_get_config('code_share');
		 }
	}
	add_action('wp_head', 'besa_tbay_share_js');
}


/*Post Views*/
if ( !function_exists('besa_set_post_views') ) {
	function besa_set_post_views($postID) {
	    $count_key = 'besa_post_views_count';
	    $count 		 = get_post_meta($postID, $count_key, true);
	    if( $count == '' ){
	        $count = 1;
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '1');
	    }else{
	        $count++;
	        update_post_meta($postID, $count_key, $count);
	    }
	}
}

if ( !function_exists('besa_track_post_views') ) {
	function besa_track_post_views ($post_id) {
	    if ( !is_single() ) return;
	    if ( empty ( $post_id) ) {
	        global $post;
	        $post_id = $post->ID;    
	    }
	    besa_set_post_views($post_id);
	}
	add_action( 'wp_head', 'besa_track_post_views');
}

if ( !function_exists('besa_get_post_views') ) {
	function besa_get_post_views($postID, $text = ''){
	    $count_key = 'besa_post_views_count';
	    $count = get_post_meta($postID, $count_key, true);

	    if( $count == '' ){
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '0');
	        return "0";
	    }
	    return $count.$text;
	}
}

/*Get Preloader*/
if ( ! function_exists( 'besa_get_select_preloader' ) ) {
	add_action( 'wp_body_open', 'besa_get_select_preloader', 10 );
    function besa_get_select_preloader( ) {
 
 		$enable_preload = besa_tbay_get_global_config('preload',false);

    	if( !$enable_preload ) return;

    	$preloader 	= besa_tbay_get_global_config('select_preloader', 'loader1');
    	$media 		= besa_tbay_get_global_config('media-preloader');
    	
    	if( isset($preloader) ) {
	    	switch ($preloader) {
	    		case 'loader1': 
	    			?>
	                <div class="tbay-page-loader">
					  	<div id="loader"></div>
					  	<div class="loader-section section-left"></div>
					  	<div class="loader-section section-right"></div>
					</div>
	    			<?php
	    			break;    		

	    		case 'loader2':
	    			?>
					<div class="tbay-page-loader">
					    <div class="tbay-loader tbay-loader-two">
					    	<span></span>
					    	<span></span>
					    	<span></span>
					    	<span></span>
					    </div>
					</div>
	    			<?php
	    			break;    		
	    		case 'loader3':
	    			?>
					<div class="tbay-page-loader">
					    <div class="tbay-loader tbay-loader-three">
					    	<span></span>
					    	<span></span>
					    	<span></span>
					    	<span></span>
					    	<span></span>
					    </div>
					</div>
	    			<?php
	    			break;    		
	    		case 'loader4':
	    			?>
					<div class="tbay-page-loader">
					    <div class="tbay-loader tbay-loader-four"> <span class="spinner-cube spinner-cube1"></span> <span class="spinner-cube spinner-cube2"></span> <span class="spinner-cube spinner-cube3"></span> <span class="spinner-cube spinner-cube4"></span> <span class="spinner-cube spinner-cube5"></span> <span class="spinner-cube spinner-cube6"></span> <span class="spinner-cube spinner-cube7"></span> <span class="spinner-cube spinner-cube8"></span> <span class="spinner-cube spinner-cube9"></span> </div>
					</div>
	    			<?php
	    			break;    		
	    		case 'loader5':
	    			?>
					<div class="tbay-page-loader">
					    <div class="tbay-loader tbay-loader-five"> <span class="spinner-cube-1 spinner-cube"></span> <span class="spinner-cube-2 spinner-cube"></span> <span class="spinner-cube-4 spinner-cube"></span> <span class="spinner-cube-3 spinner-cube"></span> </div>
					</div>
	    			<?php
	    			break;    		
	    		case 'loader6':
	    			?>
					<div class="tbay-page-loader">
					    <div class="tbay-loader tbay-loader-six"> <span class=" spinner-cube-1 spinner-cube"></span> <span class=" spinner-cube-2 spinner-cube"></span> </div>
					</div>
	    			<?php
	    			break;

	    		case 'custom_image':
	    			?>
					<div class="tbay-page-loader loader-img">
						<?php if( isset($media['url']) && !empty($media['url']) ): ?>
					   		<img alt="<?php echo ( !empty($media['alt']) ) ? esc_attr( $media['alt'] ) : ''; ?>" src="<?php echo esc_url($media['url']); ?>">
						<?php endif; ?>
					</div>
	    			<?php
	    			break;
	    			
	    		default:
	    			?>
	    			<div class="tbay-page-loader">
					  	<div id="loader"></div>
					  	<div class="loader-section section-left"></div>
					  	<div class="loader-section section-right"></div>
					</div>
	    			<?php
	    			break;
	    	}
	    }
     	
    }
}

if ( !function_exists('besa_gallery_atts') ) {

	add_filter( 'shortcode_atts_gallery', 'besa_gallery_atts', 10, 3 );
	
	/* Change attributes of wp gallery to modify image sizes for your needs */
	function besa_gallery_atts( $output, $pairs, $atts ) {

			
		if ( isset($atts['columns']) && $atts['columns'] == 1 ) {
			//if gallery has one column, use large size
			$output['size'] = 'full';
		} else if ( isset($atts['columns']) && $atts['columns'] >= 2 && $atts['columns'] <= 4 ) {
			//if gallery has between two and four columns, use medium size
			$output['size'] = 'full';
		} else {
			//if gallery has more than four columns, use thumbnail size
			$output['size'] = 'full';
		}
	
		return $output;
	
	}
}

if ( !function_exists('besa_get_custom_menu') ) {

	
	/* Change attributes of wp gallery to modify image sizes for your needs */
	function besa_get_custom_menu( $menu_id ) {

		$_id = besa_tbay_random_key();

        $args = array(
            'menu'              => $menu_id,
            'container_class'   => 'nav',
            'menu_class'        => 'menu',
            'fallback_cb'       => '',
            'before'            => '',
            'after'             => '',
            'echo'              => true,
            'menu_id'           => 'menu-'.$menu_id.'-'.$_id
        );

        $output = wp_nav_menu($args);

	
		return $output;
	
	}
}

/*Set excerpt show enable default*/
if ( ! function_exists( 'besa_tbay_edit_post_show_excerpt' ) ) {
	function besa_tbay_edit_post_show_excerpt() {
	  $user = wp_get_current_user();
	  $unchecked = get_user_meta( $user->ID, 'metaboxhidden_post', true );
	  if( is_array($unchecked) ) {
		$key = array_search( 'postexcerpt', $unchecked );
		if ( FALSE !== $key ) {
		   array_splice( $unchecked, $key, 1 );
		   update_user_meta( $user->ID, 'metaboxhidden_post', $unchecked );
		}
	  }
	}
	add_action( 'admin_init', 'besa_tbay_edit_post_show_excerpt', 10 );
}

if( ! function_exists( 'besa_texttrim')) {
	function besa_texttrim( $str ) {
		return trim(preg_replace("/('|\"|\r?\n)/", '', $str)); 
	}
}

/*Get query*/
if ( !function_exists('besa_tbay_get_boolean_query_var') ) {
    function besa_tbay_get_boolean_query_var($config) {
        $active = besa_tbay_get_config($config,true);

        $active = (isset($_GET[$config])) ? $_GET[$config] : $active;

        return (boolean)$active;
    }
}

if ( !function_exists('besa_tbay_archive_blog_size_image') ) {
    function besa_tbay_archive_blog_size_image() {
        $blog_size = besa_tbay_get_config('blog_image_sizes', 'medium');

        $blog_size = (isset($_GET['blog_image_sizes'])) ? $_GET['blog_image_sizes'] : $blog_size;

        return $blog_size;
    }
}
add_filter( 'besa_archive_blog_size_image', 'besa_tbay_archive_blog_size_image' );

if ( !function_exists('besa_tbay_archive_image_position') ) {
    function besa_tbay_archive_image_position() {
        $position = besa_tbay_get_config('image_position', 'top');

        $position = (isset($_GET['image_position'])) ? $_GET['image_position'] : $position;

        return $position;
    }
}
add_filter( 'besa_archive_image_position', 'besa_tbay_archive_image_position' );

if ( !function_exists('besa_tbay_categories_blog_type') ) {
    function besa_tbay_categories_blog_type() {
        $type = besa_tbay_get_config('categories_type', 'type-1');

        $type = (isset($_GET['categories_type'])) ? $_GET['categories_type'] : $type;

        return $type;
    }
}

// cart Postion
if ( !function_exists('besa_tbay_header_mobile_position') ) {
    function besa_tbay_header_mobile_position() {
       
		$position = besa_tbay_get_config('header_mobile', 'v1');

        $position = ( isset($_GET['header_mobile']) ) ? $_GET['header_mobile'] : $position;

        return $position;

    }
    add_filter( 'besa_header_mobile_position', 'besa_tbay_header_mobile_position' ); 
}

if ( !function_exists('besa_tbay_offcanvas_smart_menu') ) {
    function besa_tbay_offcanvas_smart_menu() {
		besa_tbay_get_page_templates_parts('device/offcanvas-smartmenu');
	}
	add_action('besa_before_theme_header', 'besa_tbay_offcanvas_smart_menu', 10);
}

if ( !function_exists('besa_tbay_the_topbar_mobile') ) {
    function besa_tbay_the_topbar_mobile() {  
		if( !besa_tbay_get_config('mobile_header', true) ) return;

        $position = apply_filters( 'besa_header_mobile_position', 10,2 ); 

        besa_tbay_get_page_templates_parts('device/topbar-mobile', $position);
	}
	add_action('besa_before_theme_header', 'besa_tbay_the_topbar_mobile', 20);
}

if ( !function_exists('besa_tbay_custom_form_login') ) {
    function besa_tbay_custom_form_login() {
		if ( !besa_catalog_mode_active() && defined('BESA_WOOCOMMERCE_ACTIVED') && BESA_WOOCOMMERCE_ACTIVED ) {
			wc_get_template_part('myaccount/custom-form-login'); 
		}
	}
	add_action('besa_before_theme_header', 'besa_tbay_custom_form_login', 30);
}


if ( !function_exists('besa_tbay_footer_mobile') ) {
    function besa_tbay_footer_mobile() {
		if( besa_active_mobile_footer_icon() ) {
			besa_tbay_get_page_templates_parts('device/footer-mobile');
		}
	}
	add_action('besa_before_theme_header', 'besa_tbay_footer_mobile', 40);
}  

if ( !function_exists( 'besa_tbay_autocomplete_suggestions' ) ) {
	add_action( 'wp_ajax_besa_autocomplete_search', 'besa_tbay_autocomplete_suggestions' );
	add_action( 'wp_ajax_nopriv_besa_autocomplete_search', 'besa_tbay_autocomplete_suggestions' );
    function besa_tbay_autocomplete_suggestions() {
		$args = array( 
			'post_status'         => 'publish',
			'orderby'         	  => 'relevance',
			'posts_per_page'      => -1,
			'ignore_sticky_posts' => 1,
			'suppress_filters'    => false,
		);

		if( ! empty( $_REQUEST['query'] ) ) {
			$search_keyword = $_REQUEST['query'];
			$args['s'] = sanitize_text_field( $search_keyword );
		}		

		if( ! empty( $_REQUEST['post_type'] ) ) {
			$post_type = strip_tags( $_REQUEST['post_type'] );
		}		 

		if( ! empty( $_REQUEST['number'] ) ) {
			$number 	= (int) $_REQUEST['number'];
		}

		if ( isset($_REQUEST['post_type']) && $_REQUEST['post_type'] != 'all') {
        	$args['post_type'] = $_REQUEST['post_type'];
        } 

		if ( isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'product') {
			if ( apply_filters( 'besa_search_query_in', besa_tbay_get_global_config('search_query_in', 'title') === 'all' ) ) {
                add_filter( 'posts_search', 'besa_product_ajax_search_sku', 9 );
            } else {
                add_filter('posts_search', 'besa_product_search_title', 20, 2);
            }
        } 


		if ( besa_is_Woocommerce_activated() && isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'product'  ) {
			
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$args['tax_query']['relation'] = 'AND';

			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			); 
			
            if ( ! empty( $_REQUEST['product_cat'] ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => strip_tags( $_REQUEST['product_cat'] ),
                );
            }
		}

		if( class_exists('WooCommerce_simple_auction') ) {
			if( get_option('simple_auctions_dont_mix_search') === 'yes' ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'auction',
					'operator' => 'NOT IN',
				); 
			}
		}

		$results = new WP_Query( $args );

        $suggestions = array();

        $count = $results->post_count;

		$view_all = ( ($count - $number ) > 0 ) ? true : false;
        $index = 0;
        if( $results->have_posts() ) {

        	if( $post_type == 'product' ) {
				$factory = new WC_Product_Factory(); 
			}

	        while( $results->have_posts() ) {
	        	if( $index == $number ) {
					break;
				}

				$results->the_post();

				if( $count == 1 ) {
					$result_text = esc_html__('result found with', 'besa');
				} else {
					$result_text = esc_html__('results found with', 'besa');
				}

				if( $post_type == 'product' ) {
					$product = $factory->get_product( get_the_ID() );


					$price = $product->get_price_html();
					if(  'auction' == $product->get_type() ) {
						if( class_exists('YITH_Auctions') ) {
							$auction_start = $product->get_start_date();
							$date          = strtotime( 'now' );

							if ( $date < $auction_start ) {
								$price = esc_html__('Auction not started', 'besa');
							} else {
								if ( $product->is_closed() || $product->get_is_closed_by_buy_now() ) {
									$price = esc_html__('Auction ended', 'besa');
								}
							}
						}
					}


					$suggestions[] = array(
						'value' => get_the_title(),
						'link' => get_the_permalink(),
						'price' => $price,
						'sku' => ( besa_tbay_get_config('search_query_in', 'title') === 'all' && besa_tbay_get_config('search_sku_ajax', false) && $product->get_sku() ) ? esc_html__( 'SKU:', 'besa' ) . ' ' . $product->get_sku() : '',
						'image' => $product->get_image(),
						'result' => '<span class="count">'.$count.' </span> '. $result_text .' <span class="keywork">"'. esc_html( $search_keyword ).'"</span>',
						'view_all' => $view_all,
					);
				} else {
					$suggestions[] = array(
						'value' => get_the_title(),
						'link' => get_the_permalink(),
						'image' => get_the_post_thumbnail( get_the_ID(), 'medium', '' ),
						'result' => '<span class="count">'.$count.' </span> '. $result_text .' <span class="keywork">"'. esc_html( $search_keyword ).'"</span>',
						'view_all' => $view_all,
					);
				}


				$index++;

	        }

	        wp_reset_postdata();
	    } else {
	    	$suggestions[] = array(
				'value' => ( $post_type == 'product' ) ? esc_html__( 'No products found.', 'besa' ) : esc_html__( 'No posts...', 'besa' ),
				'no_found' => true,
				'link' => '',
				'view_all' => $view_all,
			);
	    }

		echo json_encode( array(
			'suggestions' => $suggestions
		) );     

		die();
    }
}

if ( !function_exists( 'besa_add_cssclass' ) ) {
	function besa_add_cssclass($add, $class) {
	    $class = empty($class) ? $add : $class .= ' ' . $add;
	    return $class;
	}
}



/*Fix woocomce don't active*/
if ( !function_exists('besa_tbay_get_variation_swatchs') ) {
    function besa_tbay_get_variation_swatchs() {
        $swatchs = array( '' => esc_html__('None', 'besa'));

        if( !(defined('BESA_WOOCOMMERCE_ACTIVED') && BESA_WOOCOMMERCE_ACTIVED) ) return $swatchs;

        global $wc_product_attributes;
        // Array of defined attribute taxonomies.
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if ( ! empty( $attribute_taxonomies ) ) {
          foreach ( $attribute_taxonomies as $key => $tax ) {
            $attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
            $label                   = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;

            $swatchs[$attribute_taxonomy_name] = $label;
          }
        }

        return $swatchs;
    }
}

if ( !function_exists('besa_tbay_get_custom_tab_layouts') ) {
  function besa_tbay_get_custom_tab_layouts() {
    $tabs = array( '' => 'None');

    if( !(defined('BESA_WOOCOMMERCE_ACTIVED') && BESA_WOOCOMMERCE_ACTIVED) ) return $tabs;
    $args = array(
      'posts_per_page'   => -1,
      'offset'           => 0,
      'orderby'          => 'date',
      'order'            => 'DESC',
      'post_type'        => 'tbay_customtab',
      'post_status'      => 'publish',
      'suppress_filters' => true,
    );
    $posts = get_posts( $args );
    foreach ( $posts as $post ) {
      $tabs[$post->post_name] = $post->post_title;
    }
    return $tabs;
  }
}

/*Get title mobile in top bar mobile*/
if ( ! function_exists( 'besa_tbay_get_title_mobile' ) ) {
    function besa_tbay_get_title_mobile( $title = '') {
		$delimiter = ' / ';

        if ( is_search() ) {
            $title = esc_html__('Search results for','besa') . ' "' . get_search_query() . '"';
        } elseif ( is_tag() ) {
            $title = esc_html__('Posts tagged "', 'besa'). single_tag_title('', false) . '"';
        } elseif ( is_category() ) {
            $title = single_cat_title('', false);
        }  elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            $title = esc_html__('Articles posted by ', 'besa') . $userdata->display_name;
        } elseif ( is_404() ) {
            $title = esc_html__('Error 404', 'besa');
        } elseif (is_category()) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
            $title = single_cat_title('', false);
            
        } elseif (is_day()) {
            $title = get_the_time('d');
        } elseif (is_month()) {
            $title = get_the_time('F');
        } elseif (is_year()) {
            $title = get_the_time('Y');
        } elseif ( is_single()  && !is_attachment()) {
            $title = get_the_title();
        } else {
            $title = get_the_title();
        }

        return $title;
    }
    add_filter( 'besa_get_filter_title_mobile', 'besa_tbay_get_title_mobile' );
}


if ( ! function_exists( 'besa_tbay_get_cookie' ) ) { 
	function besa_tbay_get_cookie($name = '') {
		$check = ( isset($_COOKIE[$name]) && !empty($_COOKIE[$name]) ) ? (boolean)$_COOKIE[$name] : false;
		return $check;
	}
}

if ( ! function_exists( 'besa_tbay_active_newsletter_sidebar' ) ) { 
	function besa_tbay_active_newsletter_sidebar() {
		$active = false;

		$cookie = besa_tbay_get_cookie('hiddenmodal');

		if( !$cookie && is_active_sidebar( 'newsletter-popup' ) ) {
			$active = true;
		}

		return $active;
	}
}

if ( ! function_exists( 'besa_yith_compare_header' ) ) {
    function besa_yith_compare_header() {
        if( class_exists( 'YITH_Woocompare' ) ) { ?>
            <?php
                global $yith_woocompare;
            ?>
            <div class="yith-compare-header product">
                <a href="<?php echo esc_url($yith_woocompare->obj->view_table_url()); ?>" class="compare added">
					<i class="tb-icon tb-icon-sync"></i>
					<?php apply_filters( 'besa_get_text_compare', ''); ?>
                </a>
            </div>
    <?php }
    }
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
if ( ! function_exists( 'besa_pingback_header' ) ) {
	function besa_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}
	add_action( 'wp_head', 'besa_pingback_header', 30 );
}


if ( ! function_exists( 'besa_tbay_check_data_responsive' ) ) {
    function besa_tbay_check_data_responsive($columns, $desktop, $desktopsmall, $tablet, $landscape_mobile, $mobile) {
    	$data_array = array();

		$data_array['desktop']          =      isset($desktop) ? $desktop : $columns;
		$data_array['desktopsmall']     =      isset($desktopsmall) ? $desktopsmall : 3;
		$data_array['tablet']           =      isset($tablet) ? $tablet : 3;
		$data_array['landscape']        =      isset($landscape_mobile) ? $landscape_mobile : 3;
		$data_array['mobile']           =      isset($mobile) ? $mobile : 2;

        return $data_array; 
    }
}

if ( ! function_exists( 'besa_tbay_check_data_responsive_carousel' ) ) {
    function besa_tbay_check_data_responsive_carousel($columns, $desktop, $desktopsmall, $tablet, $landscape_mobile, $mobile) {
    	$data_responsive = besa_tbay_check_data_responsive($columns, $desktop, $desktopsmall, $tablet, $landscape_mobile, $mobile);

		$datas = " data-items=\"". esc_attr($columns) ."\"";
		$datas .= " data-desktopslick=\"". esc_attr($data_responsive['desktop']) ."\"";
		$datas .= " data-desktopsmallslick=\"". esc_attr($data_responsive['desktopsmall']) ."\"";
		$datas .= " data-tabletslick=\"". esc_attr($data_responsive['tablet']) ."\"";
		$datas .= " data-landscapeslick=\"". esc_attr($data_responsive['landscape']) ."\"";
		$datas .= " data-mobileslick=\"". esc_attr($data_responsive['mobile']) ."\"";

        return $datas;
    }
}


if ( ! function_exists( 'besa_tbay_check_data_responsive_grid' ) ) {
    function besa_tbay_check_data_responsive_grid($columns, $desktop, $desktopsmall, $tablet, $landscape_mobile, $mobile) {

    	$data_responsive = besa_tbay_check_data_responsive($columns, $desktop, $desktopsmall, $tablet, $landscape_mobile, $mobile);

		$datas  = "";
		$datas .= " data-xlgdesktop=\"" . esc_attr($columns) ."\"";
		$datas .= " data-desktop=\"" . esc_attr($data_responsive['desktop']) ."\"";
		$datas .= " data-desktopsmall=\"" . esc_attr($data_responsive['desktopsmall']) ."\"";
		$datas .= " data-tablet=\"" . esc_attr($data_responsive['tablet']) ."\"";
		$datas .= " data-landscape=\"" . esc_attr($data_responsive['landscape']) ."\"";
		$datas .= " data-mobile=\"" . esc_attr($data_responsive['mobile']) ."\"";

        return $datas;
    }
}

if ( ! function_exists( 'besa_tbay_check_data_carousel' ) ) {
    function besa_tbay_check_data_carousel($rows, $nav_type, $pagi_type, $loop_type, $auto_type, $autospeed_type, $disable_mobile) {
    	$data_array = array(); 

        $data_array['rows']				= isset($rows) ? $rows : 1;
        $data_array['nav'] 				= ($nav_type == 'yes') ? true : false;
        $data_array['pagination'] 		= ($pagi_type == 'yes') ? true : false;
        $data_array['loop'] 			= ($loop_type == 'yes') ? true : false;
        $data_array['auto'] 			= ($auto_type == 'yes') ? true : false;
        $data_array['autospeed'] 		= ( !empty($autospeed_type) ) ? $autospeed_type : 500;
        $data_array['disable_mobile'] 	= ($disable_mobile == 'yes') ? true : false;

        return $data_array;
    }
}

if ( ! function_exists( 'besa_tbay_data_carousel' ) ) {
    function besa_tbay_data_carousel($rows, $nav_type, $pagi_type, $loop_type, $auto_type, $autospeed_type, $disable_mobile) {

        $data_array = besa_tbay_check_data_carousel($rows, $nav_type, $pagi_type, $loop_type, $auto_type, $autospeed_type, $disable_mobile);

        $datas  = " data-carousel=\"owl\"";
        $datas .= " data-rows=\"" . esc_attr($data_array['rows']) ."\"";
        $datas .= " data-nav=\"" . esc_attr($data_array['nav']) ."\"";
        $datas .= " data-pagination=\"" . esc_attr($data_array['pagination']) ."\"";
        $datas .= " data-loop=\"" . esc_attr($data_array['loop']) ."\"";
        $datas .= " data-auto=\"" . esc_attr($data_array['auto']) ."\"";

        if($data_array['auto'] == 'yes') {
        	$datas .= " data-autospeed=\"" . esc_attr($data_array['autospeed']) ."\"";
        }

        $datas .= " data-unslick=\"" . esc_attr($data_array['disable_mobile']) ."\"";

        return $datas;
    }
}

if (!function_exists('besa_get_template_product')) {
	function besa_get_template_product() {

		$grid 		= besa_get_template_product_grid();
		$vertical 	= besa_get_template_product_vertical();

		$output = array_merge($grid,$vertical);

	    return $output;
	}
	add_filter( 'besa_get_template_product', 'besa_get_template_product', 10, 1 ); 
}

if (!function_exists('besa_get_template_product_grid')) {
	function besa_get_template_product_grid() {
	    $folderes = glob(BESA_THEMEROOT . '/woocommerce/item-product/inner-*');
	    $output = [];

	    foreach ($folderes as $folder) {
	        $folder = str_replace('.php', '', wp_basename($folder));
	        $value 	= str_replace("inner-", '', $folder);
	        $label = str_replace('_', ' ', str_replace('-', ' ', ucfirst($folder)));
	        $output[$value] = $label;
	    }

	    return $output;
	}
	add_filter( 'besa_get_template_product_grid', 'besa_get_template_product_grid', 10, 1 ); 
}

if (!function_exists('besa_get_template_product_vertical')) {
	function besa_get_template_product_vertical() {
	    $folderes = glob(BESA_THEMEROOT . '/woocommerce/item-product/vertical-*');
	    $output = [];

	    foreach ($folderes as $folder) {
	        $folder = str_replace('.php', '', wp_basename($folder));
	        $value 	= str_replace("inner-", '', $folder);
	        $label = str_replace('_', ' ', str_replace('-', ' ', ucfirst($folder)));
	        $output[$value] = $label;
	    }

	    return $output;
	}
	add_filter( 'besa_get_template_product_vertical', 'besa_get_template_product_vertical', 10, 1 ); 
}


if (!function_exists('besa_elementor_is_activated')) {
    function besa_elementor_is_activated() {
        return function_exists('elementor_load_plugin_textdomain');
    }
}

if (!function_exists('besa_is_Woocommerce_activated')) {
    function besa_is_Woocommerce_activated() {
        return class_exists('WooCommerce') ? true : false;
    }
}

if ( !function_exists('besa_is_woo_variation_swatches_pro') ) {
    function besa_is_woo_variation_swatches_pro() {
        return class_exists( 'Woo_Variation_Swatches_Pro' ) ? true : false;
    }
}

if ( !function_exists('besa_is_ajax_popup_quick') ) {
    function besa_is_ajax_popup_quick() {
		$active = true;

		if( besa_is_woo_variation_swatches_pro() ) {
			$active = false;
		}

        return $active;
    }
}

if ( !function_exists('besa_is_merlin_activated') ) {
    function besa_is_merlin_activated() {
        return class_exists('Merlin') ? true : false;
    }
}

if (!function_exists('besa_is_cmb2')) {
    function besa_is_cmb2() {
        return defined( 'CMB2_LOADED' ) ? true : false;
    }
}

if(!function_exists('besa_switcher_to_boolean')) {
	 function besa_switcher_to_boolean($var) {
		if( $var === 'yes' ) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('besa_sidebars_array')) {
	 function besa_sidebars_array() {
        global $wp_registered_sidebars;
        $sidebars = array();


        if ( !empty($wp_registered_sidebars) ) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }
        }

        return $sidebars;
	}
}

/**
 * Dont Update the Theme
 *
 * If there is a theme in the repo with the same name, this prevents WP from prompting an update.
 *
 * @since  1.0.0
 * @param  array $r Existing request arguments
 * @param  string $url Request URL
 * @return array Amended request arguments
 */
if(!function_exists('besa_dont_update_theme')) {
	function besa_dont_update_theme( $r, $url ) {
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) )
			return $r; // Not a theme update request. Bail immediately.
		$themes = json_decode( $r['body']['themes'] );
		$child = get_option( 'stylesheet' );
		unset( $themes->themes->$child );
		$r['body']['themes'] = json_encode( $themes );
		return $r;
	}
	add_filter( 'http_request_args', 'besa_dont_update_theme', 5, 2 );
}

if(!function_exists('besa_elements_ready_slick')) {
	function besa_elements_ready_slick() {
	    $array = [
	        'brands', 
	        'products', 
	        'posts-grid',
	        'our-team', 
	        'product-category', 
	        'product-tabs', 
	        'testimonials',
	        'product-categories-tabs',
	        'list-categories-product',
	        'custom-image-list-categories',
	        'custom-image-list-tags',
	        'product-recently-viewed',
	        'product-flash-sales',
	        'product-list-tags',
	        'product-count-down'
	    ];
	 
	    return $array; 
	}
}

if (!function_exists('besa_elements_ajax_tabs')) {
    function besa_elements_ajax_tabs()
    { 
        $array = [
            'product-categories-tabs',  
            'product-tabs',
        ];

        return $array;
    }
}


if(!function_exists('besa_elements_ready_products')) {
	function besa_elements_ready_products() {
	    $array = [
	        'products', 
	        'posts-grid',
	        'product-category', 
	        'product-tabs', 
	        'product-categories-tabs',
	        'list-categories-product',
	        'custom-image-list-categories',
	        'custom-image-list-tags',
	        'product-recently-viewed',
	        'product-flash-sales',
	        'product-list-tags',
	        'product-count-down'
	    ];
	 
	    return $array; 
	}
}

if(!function_exists('besa_elements_ready_countdown_timer')) {
	function besa_elements_ready_countdown_timer() {
	    $array = [
	        'product-flash-sales', 
	        'product-count-down'
	    ];

	    return $array;
	}
}


if(!function_exists('besa_localize_translate')) {
	function besa_localize_translate() { 
		global $wp_query; 

		$besa_hash_transient = get_transient( 'besa-hash-time' );
		if ( false === $besa_hash_transient ) {
			$besa_hash_transient = time();
			set_transient( 'besa-hash-time', $besa_hash_transient );
		}
	        
	    $config = array(
			'storage_key'  		=> apply_filters( 'besa_storage_key', 'besa_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() . $besa_hash_transient ) ),
	        'quantity_minus'    => apply_filters( 'besa_quantity_minus', '<i class="'.besa_get_icon('icon_minus').'"></i>'),
			'quantity_plus'     => apply_filters( 'besa_quantity_plus', '<i class="'.besa_get_icon('icon_plus').'"></i>'),
			'ajaxurl'			=> admin_url( 'admin-ajax.php' ),
	        'cancel'            => esc_html__('cancel', 'besa'),
	        'skin'            	=> besa_tbay_get_theme(),
	        'show_all_text'     => esc_html__('View all', 'besa'),
	        'search'            => esc_html__('Search', 'besa'),
	        'posts'             => json_encode( $wp_query->query_vars ), // everything about your loop is here
	        'max_page'          => $wp_query->max_num_pages,
	        'mobile'            => wp_is_mobile(),
	        'timeago'               => array( 
	            'suffixAgo'         => esc_html__('ago', 'besa'),
	            'suffixFromNow'     => esc_html__('from now', 'besa'),
	            'inPast'            => esc_html__('any moment now', 'besa'),
	            'seconds'           => esc_html__('less than a minute', 'besa'),
	            'minute'            => esc_html__('about a minute', 'besa'),
	            'minutes'           => esc_html__('%d minutes', 'besa'),
	            'hour'              => esc_html__('about an hour', 'besa'),
	            'hours'             => esc_html__('about %d hours', 'besa'),
	            'day'               => esc_html__('a day', 'besa'),
	            'days'              => esc_html__('%d days', 'besa'),
	            'month'             => esc_html__('about a month', 'besa'),
	            'months'            => esc_html__('%d months', 'besa'),
	            'year'              => esc_html__('about a year', 'besa'),
	            'years'             => esc_html__('%d years', 'besa'),

	        ), /*Element ready default callback*/
	        'elements_ready'  => array(
	            'slick'               => besa_elements_ready_slick(),
				'ajax_tabs'           => besa_elements_ajax_tabs(),
	            'countdowntimer'      => besa_elements_ready_countdown_timer(),
	            'products'      	  => besa_elements_ready_products(),
	        ) ,

			'icon_slick'  => array (
				'prev'		 => besa_get_icon('icon_slick_prev'),
				'next'		 => besa_get_icon('icon_slick_next'),
			)

	    );

		if( besa_elementor_is_activated() ) {    
            $config['combined_css'] = besa_get_elementor_css_print_method();
        } 


	    if( defined('BESA_WOOCOMMERCE_ACTIVED') && BESA_WOOCOMMERCE_ACTIVED ) {  

	        $position                       = apply_filters( 'besa_cart_position', 10,2 );
	        $woo_mode                       = besa_tbay_woocommerce_get_display_mode();
	        $quantity_mode                  = besa_woocommerce_quantity_mode_active();
	        // loader gif
	        $loader                         = apply_filters( 'besa_quick_view_loader_gif', BESA_IMAGES . '/ajax-loader.gif' );
	 
	        $config['current_page']         = get_query_var( 'paged' ) ? get_query_var('paged') : 1;
			
			$config['popup_cart_success']   = esc_html__('Added to cart successfully!', 'besa');

	        $config['cart_position']        = $position;
	        $config['ajax_update_quantity'] = (bool) besa_tbay_get_config('ajax_update_quantity', false);

	        $config['display_mode']         = $woo_mode;
	        $config['quantity_mode']        = $quantity_mode;
	        $config['loader']               = $loader;

	        $config['is_checkout']          =  is_checkout();
	        $config['ajax_popup_quick']     =  apply_filters( 'besa_ajax_popup_quick', besa_is_ajax_popup_quick() );

			$config['wc_ajax_url']          =  WC_AJAX::get_endpoint('%%endpoint%%');   
	        $config['checkout_url']         =  wc_get_checkout_url();
	        $config['i18n_checkout']        =  esc_html__('Checkout', 'besa');

	        $config['img_class_container']                  =  '.'.besa_get_gallery_item_class();
	        $config['thumbnail_gallery_class_element']      =  '.'.besa_get_thumbnail_gallery_item();

			$config['images_mode']        =  apply_filters('besa_woo_display_image_mode', 10, 2);
			$config['single_product']     = apply_filters('besa_active_single_product', is_product(), 10, 2);   
	    }

	    return apply_filters('besa_localize_translate', $config);
	}
}

if(!function_exists('besa_catalog_mode_active')){
    function besa_catalog_mode_active( ) {
        $active = (isset($_GET['catalog_mode'])) ? $_GET['catalog_mode'] : besa_tbay_get_config('enable_woocommerce_catalog_mode', false);

       return $active;
    }
}
if ( !function_exists('besa_wpml_is_activated') ) {
    function besa_wpml_is_activated() {
        return class_exists('SitePress');
    }
}

if ( ! function_exists( 'besa_elementor_is_edit_mode' ) ) {
	function besa_elementor_is_edit_mode() {
		return Elementor\Plugin::$instance->editor->is_edit_mode();
	}
}


if ( !function_exists('besa_tbay_get_theme') ) {
	function besa_tbay_get_theme() {
		$kin_default = 'style1';

		if( !empty(besa_tbay_get_global_config('active_theme',$kin_default)) ) {
		   return besa_tbay_get_global_config('active_theme',$kin_default);
		} else {
		   return $kin_default;
		}
	}
}
if ( !function_exists('besa_tbay_get_themes') ) {
	function besa_tbay_get_themes() {
		$themes = array();

		$themes['style1'] = array(
			'title' => esc_html__( 'Besa Style 1', 'besa' ),
			'img'   => BESA_ASSETS_IMAGES . '/active_theme/style1.jpg'
		);

		$themes['style2'] = array(
			'title' => esc_html__( 'Besa Style 2', 'besa' ),
			'img'   => BESA_ASSETS_IMAGES . '/active_theme/style2.jpg'
		);
		
		return $themes;
	}
}

if ( ! function_exists( 'besa_clean' ) ) {
	function besa_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'besa_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists( 'besa_clear_transient' ) ) {
	function besa_clear_transient() {
		delete_transient( 'besa-hash-time' );
	} 
	add_action( 'wp_update_nav_menu_item', 'besa_clear_transient', 11, 1 );
}

if (! function_exists('besa_nav_menu_get_menu_class')) {
    function besa_nav_menu_get_menu_class($layout)
    {
		$menu_class    = 'elementor-nav-menu menu nav navbar-nav megamenu';

		switch ($layout) {
			case 'vertical':
				$menu_class .= ' flex-column';
				break;

			case 'treeview':
				$menu_class = 'menu navbar-nav';
				break;
			
			default:
				$menu_class .= ' flex-row';
				break;
		}

		return  $menu_class;
    }
}

if (! function_exists('besa_get_transliterate')) {
    function besa_get_transliterate($slug)
    {
        $slug = urldecode($slug);

        if (function_exists('iconv') && defined('ICONV_IMPL') && @strcasecmp(ICONV_IMPL, 'unknown') !== 0) {
            $slug = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $slug);
        }

        return $slug;
    }
}

/**
 * Check is vendor active
 *
 * @return bool
 */
if ( ! function_exists( 'besa_woo_is_active_vendor' ) ) {
    function besa_woo_is_active_vendor() {

        if ( function_exists( 'dokan_is_store_page' ) ) {
            return true;
        }

        if ( class_exists( 'WCV_Vendors' ) ) {
            return true;
        }

        if ( class_exists( 'MVX' ) ) {
            return true;
        }

        if ( function_exists( 'wcfm_is_store_page' ) ) {
            return true;
        }

        return false;
    }
}

if (! function_exists('besa_elementor_general_widgets')) {
    function besa_elementor_general_widgets() {
        $elements = array(
            'template',  
            'heading',  
            'features', 
            'brands',
            'posts-grid',
            'our-team',
            'banner',
            'testimonials',
            'button',
            'title-width-button',
            'list-menu',
            'menu-vertical',
        );

        if( class_exists('MC4WP_MailChimp') ) {
            array_push($elements, 'newsletter');
        }


        return apply_filters('besa_general_elements_array', $elements );
    }
}

if (! function_exists('besa_elementor_header_widgets')) {
    function besa_elementor_header_widgets() {
        $elements = array(
            'site-logo',
            'nav-menu',
            'search-form',
            'banner-close',
        );

		if( besa_is_Woocommerce_activated() ) {
            array_push($elements, 'account');

            if( !besa_catalog_mode_active() ) {
                array_push($elements, 'mini-cart');
            }
        }

        if( class_exists('WOOCS_STARTER') ) {
            array_push($elements, 'currency');
        }

        if( class_exists( 'YITH_WCWL' ) ) {
            array_push($elements, 'wishlist');
        }

        if( class_exists( 'YITH_Woocompare' ) ) {
            array_push($elements, 'compare');
        } 

        if( defined('TBAY_ELEMENTOR_DEMO') ) {
            array_push($elements, 'custom-language');
        }

        return apply_filters('besa_header_elements_array', $elements );
    }
}

if (! function_exists('besa_elementor_woocommerce_widgets')) {
    function besa_elementor_woocommerce_widgets() {
		$elements = array(
            'products',
            'product-category',
            'product-tabs',
            'woocommerce-tags',
            'custom-image-list-tags',
            'product-categories-tabs',
            'list-categories-product',
            'product-recently-viewed',
            'custom-image-list-categories',
            'product-flash-sales',
            'product-count-down',
            'product-list-tags'
        );

        return apply_filters('besa_woocommerce_elements_array', $elements );
    }
}

if (! function_exists('besa_redux_elementor_to_list_addons')) {
    function besa_redux_elementor_to_list_addons( $general_widgets ) {

        $fields_general = array();

        foreach( $general_widgets as $key => $value ) {
            $id     = 'addon_el_'. str_replace("-","_", $value);

            $name = sprintf( __('Besa %s', 'besa'), ucwords( str_replace("-"," ", $value) ) );

            $fields_general[] = array(
                'id'            => $id,
                'type'          => 'switch',
                'title'         => 'Besa '. ucwords( str_replace("-"," ", $value) ),
                'default'       => true
            );
        }

        return $fields_general;

    }
}

if ( ! function_exists('besa_active_woocommerce_auction') ) {
	function besa_active_woocommerce_auction() {
		$active = false;

		if( class_exists('WooCommerce_simple_auction') || class_exists('YITH_Auctions') ) {
			$active = true;
		}

		return $active;
	}
}

