<?php
if ( ! defined( 'ABSPATH' ) || !class_exists('WooCommerce') ) {
	exit;
}

if ( ! class_exists( 'Besa_WooCommerce' ) ) :


	class Besa_WooCommerce {

		static $instance;

		/**
		 * @return osf_WooCommerce
		 */
		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Besa_WooCommerce ) ) {
				self::$instance = new Besa_WooCommerce();
			}

			return self::$instance;
		}

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 *
		 */
		public function __construct() {
			$this->includes();
			$this->init_hooks();
		}

		public function includes() {
			require_once( get_parent_theme_file_path( BESA_VENDORS . '/woocommerce/classes/class-wc-shop.php') );
			require_once( get_parent_theme_file_path( BESA_VENDORS . '/woocommerce/classes/class-wc-single.php') );
			require_once( get_parent_theme_file_path( BESA_VENDORS . '/woocommerce/classes/class-wc-cart.php') );
		}

		private function init_hooks() {
			add_action( 'after_setup_theme', array( $this, 'setup' ), 10 );
			add_action( 'after_setup_theme', array( $this, 'setup_size_image' ), 10 );

			add_action( 'widgets_init', array( $this, 'widgets_init'), 10 );

			if(besa_tbay_get_global_config('config_media',false)) {
			    remove_action( 'after_setup_theme', array( $this, 'setup_size_image' ), 10 );
			}

			add_filter( 'besa_woo_pro_des_image', array( $this, 'shop_des_image_active'), 10, 1 );

			/*Body Class*/ 
			add_filter( 'body_class', array( $this, 'body_class' ), 30, 1 );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_scripts' ), 20 );

			/*YITH Compare*/
			add_action( 'wp_print_styles', array( $this, 'compare_styles'), 200 );

			/*Quick view*/
			add_action( 'wp_enqueue_scripts', array( $this, 'quick_view_scripts'), 101 );
			if ( besa_tbay_get_config('enable_quickview', true) ) {
			    add_action( 'wp_ajax_besa_quickview_product', array( $this, 'quick_view_ajax'), 10 );
			    add_action( 'wp_ajax_nopriv_besa_quickview_product', array( $this, 'quick_view_ajax'), 10 );
			}

			add_action( 'init', array( $this, 'remove_wc_breadcrumb'), 90 );

			add_filter( 'besa_tbay_woocommerce_content_class', array( $this, 'woocommerce_content_class'), 10 );

			/**Fix YITH wishlist 3.0**/
            if ( class_exists('YITH_WCWL') && apply_filters( 'tbay_yith_wcwl_remove_text', true )) {

                $icon = get_option( 'yith_wcwl_add_to_wishlist_icon' );
                if ( 'custom' === $icon ) {

                    $custom_icon       = get_option( 'yith_wcwl_add_to_wishlist_custom_icon' );

                    if( empty( $custom_icon ) ) {
                        add_filter( 'yith_wcwl_add_to_wishlist_icon_html', array( $this, 'custom_wishlist_icon_html' ), 10, 1 );
                    }

                } 

                $added_icon = get_option( 'yith_wcwl_added_to_wishlist_icon' );

                if ('custom' === $added_icon) {
                    $added_custom_icon       = get_option( 'yith_wcwl_added_to_wishlist_custom_icon' );

                    if( empty( $added_custom_icon ) ) {
                        add_filter( 'yith_wcwl_add_to_wishlist_heading_icon_html', array( $this, 'custom_wishlist_icon_html' ), 10, 1 );
                    }
                } 
                

                add_filter('yith_wcwl_product_already_in_wishlist_text_button', array( $this, 'remove_wishlist_text'), 10, 1);
                add_filter('yith_wcwl_product_added_to_wishlist_message_button', array( $this, 'remove_wishlist_text'), 10, 1);
                add_filter('yith_wcwl_remove_from_wishlist_label', array( $this, 'remove_wishlist_text'), 10, 1);
            }

			add_filter( 'post_class', array( $this, 'post_class'), 21 );

			add_filter('woocommerce_format_sale_price', array( $this, 'price_html'), 100, 2);

			add_filter( 'body_class', array( $this, 'body_classes_product_number_mobile'), 10, 1 );


			/*Catalog mode*/
			add_filter( 'body_class', array( $this, 'body_class_woocommerce_catalog_mod'), 10, 1 );
			add_action( 'woocommerce_before_single_product_summary', array( $this, 'catalog_mode_remove_single_hook'), 10 );
			add_action( 'tbay_woocommerce_before_content_product', array( $this, 'catalog_mode_remove_shop_loop_item_hook'), 10 );
			add_action( 'yith_wcqv_product_image', array( $this, 'catalog_mode_remove_yith_wcqv_hook'), 10 );
			add_action( 'wp', array( $this, 'catalog_mode_redirect_page'), 10 );

			/**Fix customize compare style**/
			add_action('wp_print_styles', array( $this, 'add_compare_styles'), 200);

			/*Hide Variation Selector on HomePage and Shop page*/
			add_filter( 'besa_enable_variation_selector', array( $this, 'enable_variation_selector'), 10 );
			add_filter( 'body_class', array( $this, 'body_classes_enable_variation_selector'), 10 );


			/*Show Quantity on mobile*/
			add_filter( 'besa_show_quantity_mobile', array( $this, 'show_quantity_mobile'), 10, 1);
			add_filter( 'body_class', array( $this, 'body_classes_show_quantity_mobile'), 10, 1 );

			/*Remove password strength check.*/
			add_action( 'wp_print_scripts', array( $this, 'remove_password_strength'), 10 );


			if( defined( 'YITH_WCWL' ) ) {
				add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', array( $this, 'yith_wcwl_ajax_update_count'), 10 );
				add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', array( $this, 'yith_wcwl_ajax_update_count'), 10 );		
			}

			/*Change sale flash*/
			add_filter('woocommerce_sale_flash', array( $this, 'show_product_loop_sale_flash_label'), 10, 3);
			add_action( 'tbay_woocommerce_before_content_product', 'woocommerce_show_product_loop_sale_flash', 10 );
			add_action( 'tbay_woocommerce_before_content_product', array($this, 'only_feature_product_label' ), 10 );
			
			$skin = besa_tbay_get_theme();
			if ($skin === 'style1') {
				add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_only_feature_product_label'), 25 );
				add_action( 'besa_before_image_quickview', array( $this, 'single_only_feature_product_label'), 25 );
				add_action( 'besa_woocommerce_template_single_price_wrapper', 'woocommerce_show_product_loop_sale_flash', 10 );
			} 

			add_filter('gwp_affiliate_id', array( $this, 'affiliate_id'), 10);

			add_action( 'woocommerce_register_form_end', array( $this, 'social_nextend_social_register'), 10 );
            add_action( 'woocommerce_login_form_end', array( $this, 'social_nextend_social_login'), 10 );


			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'show_product_outstock_flash_html'), 20 );


			/*Page check out*/
			add_filter( 'woocommerce_paypal_icon', array( $this, 'check_out_paypal_icon'), 10, 1 );
			add_filter( 'woocommerce_checkout_fields', array( $this, 'registration_confirm_password_checkout'), 10, 1 ); 
			add_action( 'woocommerce_after_checkout_validation', array( $this, 'confirm_password_validation'), 10, 2 );

			if( class_exists('NextendSocialLogin') ) {
				add_action('woocommerce_before_customer_login_form', array( $this, 'login_social_form_buttons'), 10);
				add_action('besa_woocommerce_before_customer_login_form', array( $this, 'login_social_form_buttons'), 10);
			}


			add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'product_thumbnails_columns'), 10, 1 );

			add_action('woocommerce_before_main_content', array( $this, 'remove_result_count_loadmore'), 10);


			/*Filter mobile*/
			add_action( 'woocommerce_before_main_content', array( $this, 'filter_mobile_content_html'), 40 );
			add_action( 'woocommerce_before_shop_loop', array( $this, 'button_filter_sidebar_mobile_html') , 20 );
			add_action( 'wp_footer', array( $this, 'close_side_woocommerce_show_sidebar_btn') , 20 );

			/*Vendor Dokan page*/
			if( class_exists('WeDevs_Dokan') ) {
				/*Filter mobile*/
				add_action( 'dokan_store_profile_frame_after', array( $this, 'button_filter_sidebar_mobile_html') , 20 );
			}

			add_filter( 'besa_get_filter_title_mobile', array( $this, 'get_title_mobile'), 10 );

			/*The avatar in page my account on mobile*/
			add_action( 'woocommerce_account_navigation', array( $this, 'the_my_account_avatar'), 5 );

			/*Register Form*/
			add_action( 'woocommerce_register_form', array( $this, 'the_register_form_password_repeat'), 0 );
			add_filter('woocommerce_registration_errors', array( $this, 'registration_errors_validation'), 10,3);

			add_action('wp_ajax_woocommerce_besa_quantity_mini_cart', array($this, 'besa_quantity_mini_cart'));
			add_action('wp_ajax_nopriv_woocommerce_besa_quantity_mini_cart', array($this, 'besa_quantity_mini_cart'));
			// WC AJAX can be used for frontend ajax requests.
			add_action('wc_ajax_besa_quantity_mini_cart', array($this, 'besa_quantity_mini_cart'));
		}

		
		public function setup() {
			add_theme_support( "woocommerce" );
		}

		public function woocommerce_scripts() {
	 		$suffix = (besa_tbay_get_config('minified_js', false)) ? '.min' : BESA_MIN_JS;

	        wp_enqueue_script( 'besa-woocommerce', BESA_SCRIPTS . '/woocommerce' . $suffix . '.js', array( 'besa-script' ), BESA_THEME_VERSION, true );

	        wp_register_script( 'jquery-onepagenav', BESA_SCRIPTS . '/jquery.onepagenav' . $suffix . '.js', array( 'besa-script' ), '3.0.0', true ); 
		}

		public function custom_wishlist_icon_html( )
        {
            return '<i class="'.besa_get_icon('icon_wishlist').'"></i>';
        }
 
        public function remove_wishlist_text()
        {
            return '';
        }

		public function compare_styles() {

			if( ! class_exists( 'YITH_Woocompare' ) ) return;

	        $view_action = 'yith-woocompare-view-table';
	        if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $view_action ) ) return;

	        wp_enqueue_style( 'besa-font-tbay-custom' );

	        wp_enqueue_style( 'besa-template' );

	        add_filter( 'body_class', array( $this, 'body_classes_compare'), 30, 1 );

		}

		public function body_classes_compare( $classes ) {
			$class = 'tbay-body-compare';

			$classes[] = trim($class);

			return $classes;
		}

		public function body_class( $classes ) {

			$class  =  ( is_cart() && besa_tbay_get_config('ajax_update_quantity', false) ) ? 'tbay-ajax-update-quantity' : ''; 
			
	        $class  = besa_add_cssclass('woocommerce', $class );
 
	        if( is_product_category() ) { 
	            $class  = besa_add_cssclass('tbay-product-category', $class );
	        }

	        if ( is_cart() && WC()->cart->is_empty()  ) {
	            $class = besa_add_cssclass('empty-cart', $class );
	        }
	        

	        if( class_exists( 'Woo_Variation_Swatches' ) ) {
	                    
	            if( !class_exists( 'Woo_Variation_Swatches_Pro' ) ) {
	                $class = besa_add_cssclass('tbay-variation-free', $class );
	            }     
			}

	        $classes[] = trim($class);

	        return $classes;
		}

		public function widgets_init() {
			register_sidebar( array(
                'name'          => esc_html__( 'Product Top Archive Product', 'besa' ),
                'id'            => 'product-top-archive',
                'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'besa' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );

			$skin = besa_tbay_get_theme();
			if ($skin === 'style2') {
				register_sidebar(array(
					'name'          => esc_html__('Product Archive Sidebar Bottom', 'besa'),
					'id'            => 'product-bottom-archive',
					'description'   => esc_html__('Add widgets here to appear in bottom product archive.', 'besa'),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				));
			}

			register_sidebar(array(
				'name'          => esc_html__('After Meta Single Products Sidebar', 'besa'),
				'id'            => 'after-meta-single-product',
				'description'   => esc_html__('Add widgets here to appear in bottom product archive.', 'besa'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			));


            register_sidebar( array(
                'name'          => esc_html__( 'Product Archive Sidebar', 'besa' ),
                'id'            => 'product-archive',
                'description'   => esc_html__( 'Add widgets here to appear in Product archive left, right sidebar.', 'besa' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );            
            register_sidebar( array(
                'name'          => esc_html__( 'Product Single Sidebar', 'besa' ),
                'id'            => 'product-single',
                'description'   => esc_html__( 'Add widgets here to appear in Product single left, right sidebar.', 'besa' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );            
            register_sidebar( array(
                'name'          => esc_html__( 'Product Sidebar Mobile', 'besa' ),
                'id'            => 'sidebar-mobile',
                'description'   => esc_html__( 'Add widgets here to appear in Product archive in mobile', 'besa' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) );
		}

		public function setup_size_image() {

			$thumbnail_width = 280;
			$main_image_width = 600;
			$cropping_custom_width = 1;
			$cropping_custom_height = 1;

			// Image sizes
			update_option( 'woocommerce_thumbnail_image_width', $thumbnail_width );
			update_option( 'woocommerce_single_image_width', $main_image_width ); 

			update_option( 'woocommerce_thumbnail_cropping', 'custom' );
			update_option( 'woocommerce_thumbnail_cropping_custom_width', $cropping_custom_width );
			update_option( 'woocommerce_thumbnail_cropping_custom_height', $cropping_custom_height );

		}

		public function remove_wc_breadcrumb() {
			if( !besa_tbay_get_config('show_product_breadcrumb', false) ) {
		        remove_action( 'besa_woo_template_main_before', 'woocommerce_breadcrumb', 20, 0 );
		    } 
		}

		public function woocommerce_content_class( $class ) {
			$page = 'archive';
	        if ( is_singular( 'product' ) ) {
	            $page = 'single';
	        } 

	        if( !isset($_GET['product_'.$page.'_layout']) ) {
	            $class .= ' '.besa_tbay_get_config('product_'.$page.'_layout');
	        }  else {
	            $class .= ' '.$_GET['product_'.$page.'_layout'];
	        }

	        return $class;
		}

		public function yith_icon_wishlist() {
			return '<i class="'.besa_get_icon('icon_wishlist').'"></i><span>'.esc_html__('Add to wishlist','besa').'</span>';
		}

		public function yith_browse_wishlist_label() {
			return '<i class="'.besa_get_icon('icon_wishlist').'"></i><span>'.esc_html__('View wishlist','besa').'</span>';
		}

		public function post_class( $classes ) {

	        if ( 'product' == get_post_type() ) {
	            $classes = array_diff( $classes, array( 'first', 'last' ) );
	        }
	        return $classes;

		}

		public function product_type_query( $q ) {
	        $name = 'product_type';
	        $default = 'recent_products';

	        $product_type = besa_woocommerce_get_fillter($name, $default);
	        $args    = besa_woocommerce_meta_query($product_type);
	        $queries = array('meta_key', 'orderby', 'order', 'post__in', 'tax_query', 'meta_query');
	        if ( function_exists( 'woocommerce_products_will_display' ) && $q->is_main_query() ) :
	            foreach($queries as $query){
	                if(isset($args[$query])){
	                    $q->set( $query, $args[$query] );
	                }
	            }
	        endif;
		}

		public function product_type_fillter() {
			$default = 'best_selling';
			$options = array(
				'best_selling'      => esc_html__('Best Selling', 'besa'),
				'featured_product'  => esc_html__('Featured Products', 'besa'),
				'recent_product'    => esc_html__('Recent Products', 'besa'),
				'on_sale'           => esc_html__('On Sale', 'besa'),
				'random_product'    => esc_html__('Random Products', 'besa')
			);
			$name = 'product_type';
			besa_woocommerce_product_fillter($options, $name, $default);
		}

		public function product_per_page_fillter() {
			$columns = besa_tbay_get_config('product_columns', 4);
	        $default = besa_tbay_get_config('number_products_per_page');
	        $options= array();
	        for($i=1; $i<=5; $i++){
	            $options[$i*$columns] =  $i*$columns.' '.esc_html__( ' products', 'besa');
	        }
	        $options['-1'] = esc_html__('All products', 'besa' );
	        $name = 'product_per_page';
	        besa_woocommerce_product_fillter($options, $name, $default);
		}

		public function product_category_fillter() {
			$taxonomy       = 'product_cat';
	        $orderby        = 'name';  
	        $pad_counts     = 0;      // 1 for yes, 0 for no
	        $hierarchical   = 1;      // 1 for yes, 0 for no   
	        $empty          = 0;
	        $posts_per_page =  -1;

	        $args = array(
	            'taxonomy'       => $taxonomy, 
	            'orderby'        => $orderby,
	            'posts_per_page' => $posts_per_page,
	            'pad_counts'     => $pad_counts,
	            'hierarchical'   => $hierarchical,
	            'hide_empty'     => $empty
	        );

	        $all_categories = get_categories( $args );

	        $options = array();
	        $class = array();
	        $options['-1'] = esc_html__('All Categories', 'besa' );
	        $class[] = 'level-0';
	        $default = esc_html__('All Categories', 'besa' );
	        foreach ($all_categories as $cat) {
	            if($cat->category_parent == 0) {
	                $cat_name   =   $cat->name;    
	                $cat_id     =   $cat->term_id;    
	                $cat_slug   =   $cat->slug;    
	                $count      =   $cat->count;
	                $level      =   0;

	                $options[$cat_slug]      =  $cat_name.'('.$count.')';
	                $class[]                 = 'level-'.$level;

	                $taxonomy       =   'product_cat';
	                $orderby        =   'name';  
	                $pad_counts     =   0;      // 1 for yes, 0 for no
	                $hierarchical   =   1;      // 1 for yes, 0 for no   
	                $empty          =   0;
	                $posts_per_page =  -1;


	                $args2 = array(
	                        'child_of'      => 0,
	                        'parent'         => $cat_id,
	                        'taxonomy'       => $taxonomy, 
	                        'orderby'        => $orderby,
	                        'posts_per_page' => $posts_per_page,
	                        'pad_counts'     => $pad_counts,
	                        'hierarchical'   => $hierarchical,
	                        'hide_empty'     => $empty
	                );

	                $sub_cats = get_categories( $args2 );


	                if($sub_cats) {
	                    $level ++;

	                    foreach($sub_cats as $sub_category) {

	                        $sub_cat_name               =   $sub_category->name;    
	                        $sub_cat_id                 =   $sub_category->term_id;    
	                        $sub_cat_slug               =   $sub_category->slug;    
	                        $sub_count                  =   $sub_category->count;
	                        $class[]                    =  'level-'.$level;

	                        $options[$sub_cat_slug]     =  $sub_cat_name.'('.$sub_count.')';


	                        $taxonomy       =   'product_cat';
	                        $orderby        =   'name';  
	                        $pad_counts     =   0;      // 1 for yes, 0 for no
	                        $hierarchical   =   1;      // 1 for yes, 0 for no   
	                        $empty          =   0;
	                        $posts_per_page =  -1;


	                        $args2 = array(
	                                'child_of'      => 0,
	                                'parent'         => $sub_cat_id,
	                                'taxonomy'       => $taxonomy, 
	                                'orderby'        => $orderby,
	                                'posts_per_page' => $posts_per_page,
	                                'pad_counts'     => $pad_counts,
	                                'hierarchical'   => $hierarchical,
	                                'hide_empty'     => $empty
	                        );

	                        $sub_cats = get_categories( $args2 );


	                        if($sub_cats) {
	                            $level ++;

	                            foreach($sub_cats as $sub_category) {

	                                $sub_cat_name               =   $sub_category->name;    
	                                $sub_cat_id                 =   $sub_category->term_id;    
	                                $sub_cat_slug               =   $sub_category->slug;    
	                                $sub_count                  =   $sub_category->count;
	                                $class[]                    =  'level-'.$level;

	                                $options[$sub_cat_slug]     =  $sub_cat_name.'('.$sub_count.')';
	                            }
	                        }

	                    }
	                }

	            }
	        }
	                        
	        $name = 'product_category';

	        besa_woocommerce_product_fillter($options, $name, $default, $class);
		}

		
		public function price_html( $price, $product ) {
			return preg_replace('@(<del.*>.*?</del>).*?(<ins>.*?</ins>)@misx', '$2 $1', $price);
		}
		
		public function shop_load_more_button_html() {
			global $wp_query;
 
	        if (  $wp_query->max_num_pages > 1 ) {
	            ?>
	           <div class="tbay-pagination-load-more">
	                <a href="javascript:void(0);" data-loading-text="<?php esc_attr_e('Loading...', 'besa'); ?>" data-loadmore="true">
	                    <i class="tb-icon tb-icon-plus"></i>
	                    <span class="text"><?php esc_html_e('Load More', 'besa'); ?></span>
	                </a>
	           </div>

	       <?php }
		}

		

		public function body_classes_product_number_mobile( $classes ) {
			$columns = besa_tbay_get_config('mobile_product_number', 'two');

	        if( isset($columns) ) {
	            $class = 'tbay-body-mobile-product-'.$columns;
	        }

	        $classes[] = trim($class);

	        return $classes;
		}

		public function body_class_woocommerce_catalog_mod( $classes ) {
	        $class = '';
	        $active = besa_catalog_mode_active();
	        if( isset($active) && $active ) {  
	            $class = 'tbay-body-woocommerce-catalog-mod';
	        }

	        $classes[] = trim($class);

	        return $classes;
		}

		public function catalog_mode_remove_single_hook() {
			$active = besa_catalog_mode_active();

	        if( isset($active) && $active ) { 
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);     
			}
		}

		public function catalog_mode_remove_shop_loop_item_hook() {
			$active = besa_catalog_mode_active();

	        if( isset($active) && $active ) { 
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			}
		}

		public function catalog_mode_remove_yith_wcqv_hook() {
			$active = besa_catalog_mode_active();

	        if( isset($active) && $active ) {  

	            if ( defined( 'YITH_WCQV' ) ) {
	                remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
	            }
	        }
		}

		public function catalog_mode_redirect_page() {
			$active = besa_catalog_mode_active();
	        if( isset($active) && $active ) {  
	           
	            $cart     = is_page( wc_get_page_id( 'cart' ) );
	            $checkout = is_page( wc_get_page_id( 'checkout' ) );

	            wp_reset_postdata();

	            if ( $cart || $checkout ) {

	                wp_redirect( home_url() );
	                exit;

	            }
	        }
		}
		public function add_compare_styles()
        {
            if( ! class_exists( 'YITH_Woocompare' ) ) return;
            $view_action = 'yith-woocompare-view-table';
            if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $view_action ) ) return;
            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'simple-line-icons' );
            wp_enqueue_style( 'besa-style' );
        }

		public function enable_variation_selector() {
			$active = besa_tbay_get_config('enable_variation_swatch', false);

	        $active = (isset($_GET['variation-selector'])) ? $_GET['variation-selector'] : $active;

	        if( class_exists( 'Woo_Variation_Swatches_Pro' ) ) {
	            $active = false;
	        }

	        return $active;
		}

		public function body_classes_enable_variation_selector( $classes ) {

			$class = '';
	        $active = apply_filters( 'besa_enable_variation_selector', 10,2 );
	        if( !(isset($active) && $active) ) {  
	            $class = 'tbay-hide-variation-selector';
	        }

	        $classes[] = trim($class);

	        return $classes;

		}

		public function show_quantity_mobile() {
			$active = besa_tbay_get_config('enable_quantity_mobile', false);

			$active = (isset($_GET['quantity_mobile'])) ? $_GET['quantity_mobile'] : $active;

			return $active;
		}

		public function body_classes_show_quantity_mobile( $classes ) {
	  		$class = '';
	        $active = apply_filters( 'besa_show_quantity_mobile', 10,2 );
	        if( isset($active) && $active ) {  
	            $class = 'tbay-show-quantity-mobile';
	        }

	        $classes[] = trim($class);

	        return $classes;
		}

		public function remove_password_strength() {
			$active = besa_tbay_get_config('show_woocommerce_password_strength', true);

	        if( isset($active) && !$active ) {
	            wp_dequeue_script( 'wc-password-strength-meter' );
	        }
		}

		public function yith_wcwl_ajax_update_count() {
			$wishlist_count = YITH_WCWL()->count_products();

		    wp_send_json( array(
		    'count' => $wishlist_count
		    ) );
		}

		public function show_product_loop_sale_flash_label( $original, $post, $product ) {

	        $saleTag = $original;

	        $format                 =  besa_tbay_get_config('sale_tags', 'custom');
	        $enable_label_featured  =  besa_tbay_get_config('enable_label_featured', true);

	        if ($format == 'custom') {
	            $format = besa_tbay_get_config('sale_tag_custom', '-{percent-diff}%');
	        }

	        $priceDiff = 0;
	        $percentDiff = 0;
	        $regularPrice = '';
	        $salePrice = $percentage = $return_content = '';

	        $decimals   =  wc_get_price_decimals();
	        $symbol   	=  get_woocommerce_currency_symbol();

	        $_product_sale   = $product->is_on_sale();
	        $featured        = $product->is_featured();

	        if( $featured && $enable_label_featured ) {
	            $return_content  = '<span class="featured">'. besa_tbay_get_config('custom_label_featured', esc_html__('Hot', 'besa')) .'</span>';
	        }


	        if( !empty($product) && $product->is_type( 'variable' ) ){
	            $default_attributes = besa_get_default_attributes( $product );
	            $variation_id = besa_find_matching_product_variation( $product, $default_attributes );

	            if( empty($variation_id) ) return;

	            $variation      = wc_get_product($variation_id);

	            $_product_sale  = $variation->is_on_sale();

	            if( $_product_sale ) {
	                $regularPrice   = (float) get_post_meta($variation_id, '_regular_price', true);
	                $salePrice      = (float) get_post_meta($variation_id, '_price', true);   
	            }
	        } else {
	            $salePrice = (float) get_post_meta($product->get_id(), '_price', true);
	            $regularPrice = (float) get_post_meta($product->get_id(), '_regular_price', true);
	        }


	        if (!empty($regularPrice) && !empty($salePrice ) && $regularPrice > $salePrice ) {
	            $priceDiff = $regularPrice - $salePrice;
	            $percentDiff = round($priceDiff / $regularPrice * 100);
	            
	            $parsed = str_replace('{price-diff}', number_format((float)$priceDiff, $decimals, '.', ''), $format);
	            $parsed = str_replace('{symbol}', $symbol, $parsed);
	            $parsed = str_replace('{percent-diff}', $percentDiff, $parsed);
	            $percentage = '<span class="saled">'. $parsed .'</span>';
	        }

	        if( !empty($_product_sale ) )  {
	            $percentage .= $return_content;
	        } else {
	            $percentage = '<span class="saled">'. esc_html__( 'Sale', 'besa' ) . '</span>';
	            $percentage .= $return_content;
	        }

	        echo '<span class="onsale">'. trim($percentage) . '</span>';
		}

		public function only_sale_product_label( $original, $post, $product ) {

	        $saleTag = $original;

	        $format                 =  besa_tbay_get_config('sale_tags', 'custom');

	        if ($format == 'custom') {
	            $format = besa_tbay_get_config('sale_tag_custom', '-{percent-diff}%');
	        }

	        $priceDiff = 0;
	        $percentDiff = 0;
	        $regularPrice = '';
	        $salePrice = $percentage = $return_content = '';

	        $decimals   =  wc_get_price_decimals();
	        $symbol   	=  get_woocommerce_currency_symbol();

	        $_product_sale   = $product->is_on_sale();

	        if( !empty($product) && $product->is_type( 'variable' ) ){
	            $default_attributes = besa_get_default_attributes( $product );
	            $variation_id = besa_find_matching_product_variation( $product, $default_attributes );

	            if( empty($variation_id) ) return;

	            $variation      = wc_get_product($variation_id);

	            $_product_sale  = $variation->is_on_sale();

	            if( $_product_sale ) {
	                $regularPrice   = (float) get_post_meta($variation_id, '_regular_price', true);
	                $salePrice      = (float) get_post_meta($variation_id, '_price', true);   
	            }
	        } else {
	            $salePrice 		= (float) get_post_meta($product->get_id(), '_price', true);
	            $regularPrice 	= (float) get_post_meta($product->get_id(), '_regular_price', true);
	        }


	        if (!empty($regularPrice) && !empty($salePrice ) && $regularPrice > $salePrice ) {
	            $priceDiff = $regularPrice - $salePrice;
	            $percentDiff = round($priceDiff / $regularPrice * 100);
	            
	            $parsed = str_replace('{price-diff}', number_format((float)$priceDiff, $decimals, '.', ''), $format);
	            $parsed = str_replace('{symbol}', $symbol, $parsed);
	            $parsed = str_replace('{percent-diff}', $percentDiff, $parsed);
	            $percentage = '<span class="saled">'. $parsed .'</span>';
	        }

	        if( !empty($_product_sale ) )  {
	            $percentage .= $return_content;
	        } else {
	            $percentage = '<span class="saled">'. esc_html__( 'Sale', 'besa' ) . '</span>';
	            $percentage .= $return_content;
	        }

	        echo '<span class="onsale">'. trim( $percentage ) . '</span>';
		}

		public function only_feature_product_label() {
	  		global $product;

	        $featured        = $product->is_featured();

			$_product_sale   = $product->is_on_sale();

			if( $_product_sale ) return;
	        
	        $return_content = '';
	        if( $featured ) {

	            $enable_label_featured  =  besa_tbay_get_config('enable_label_featured', true);

	            if( $featured && $enable_label_featured ) {
	                $return_content  .= '<span class="onsale"><span class="featured">'. besa_tbay_get_config('custom_label_featured', esc_html__('Hot', 'besa')) .'</span></span>';

	                echo trim($return_content);
	            }  

	        }
		}

		public function single_only_feature_product_label() {
	  		global $product;

	        $featured        = $product->is_featured();
	        
	        $return_content = '';
	        if( $featured ) {

	            $enable_label_featured  =  besa_tbay_get_config('enable_label_featured', true);

	            if( $featured && $enable_label_featured ) {
	                $return_content  .= '<span class="onsale"><span class="featured">'. besa_tbay_get_config('custom_label_featured', esc_html__('Hot', 'besa')) .'</span></span>';

	                echo trim($return_content);
	            }  

	        }
		}

		public function quick_view_scripts() {
			if ( !besa_tbay_get_config('enable_quickview', true)) return;
	        wp_enqueue_script( 'wc-add-to-cart-variation' );
	        wp_enqueue_script('wc-single-product');
		}

		public function quick_view_ajax() {
			if ( ! isset( $_GET['product_id'] ) ) {
				die();
			}

			$args = array(
				'post_type' => 'product',
				'post__in' => array($_GET['product_id'])
			);
			$query = new WP_Query($args);

			ob_start();
			if ( $query->have_posts() ) {
				while ($query->have_posts()): $query->the_post();
					wc_get_template_part( 'content', 'product-quickview' );
				endwhile;
			} 
			echo ob_get_clean();
	        die;
		}

		public function affiliate_id() {
			return 2403;
		}

		public function social_nextend_social_register() {    
            if ( class_exists('NextendSocialLogin') ) {
                echo '<div class="social-log"><span>'. esc_html__('Or connect with', 'besa') .'</span></div>';
            }
        }

        public function social_nextend_social_login() {
            if ( class_exists('NextendSocialLogin') ) {
                echo '<div class="social-log"><span>'. esc_html__('Or login with', 'besa') .'</span></div>';
            }
        }

		public function show_product_outstock_flash_html( $html ) {
			global $product;
	        $return_content = '';

	        if( $product->is_type( 'simple' ) ) {
	            if ( $product->is_on_sale() &&  ! $product->is_in_stock() ) {
	                $return_content .= '<span class="out-stock out-stock-sale"><span>'. esc_html__('Out of stock', 'besa') .'</span></span>';
	            } else if ( ! $product->is_in_stock() ) {
	               $return_content .= '<span class="out-stock"><span>' . esc_html__('Out of stock', 'besa') .'</span></span>';
	            }
	        }


	        echo trim($return_content);
		}

		public function check_out_paypal_icon() {
			return BESA_IMAGES. '/paypal.png';
		}

		public function login_social_form_buttons() {
			add_action('woocommerce_login_form_end', 'NextendSocialLogin::addLoginFormButtons');
			add_action('woocommerce_register_form_end', 'NextendSocialLogin::addLoginFormButtons');
		}

		public function product_thumbnails_columns() {
			$columns = besa_tbay_get_config('number_product_thumbnail', 4);

	        if(isset($_GET['number_product_thumbnail']) && !empty($_GET['number_product_thumbnail']) && is_numeric($_GET['number_product_thumbnail']) ) {
	            $columns = $_GET['number_product_thumbnail'];
	        } else {
	            $columns = besa_tbay_get_config('number_product_thumbnail', 4);
	        }

	        return $columns;
		}

		public function remove_result_count_loadmore() {

			$pagination_style = ( isset($_GET['pagination_style']) ) ? $_GET['pagination_style'] : besa_tbay_get_config('product_pagination_style', 'number');

	        if( isset($pagination_style) && ($pagination_style == 'loadmore') ) {

	            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

	        }

		}

		public function filter_mobile_content_html() {
			
			if( !(is_product_category() || is_product_tag() || is_product_taxonomy() || is_shop() || besa_woo_is_vendor_page() ) ) return;

			
	        if(is_active_sidebar('sidebar-mobile')) {
	            ?>

	            <div class="filter-mobile">
	                <div class="content">
	                    <h3 class="heading-title"><?php echo esc_html__('Product Filter', 'besa'); ?></h3>
	                    <a href="javascript:;" class="close"><i class="tb-icon tb-icon-cross2"></i></a>
	                    <div class="sidebar">
	                        <?php dynamic_sidebar('sidebar-mobile'); ?>
	                    </div>
	                </div>
	            </div>

	            <?php
	        }
		}

		public function button_filter_sidebar_mobile_html() {
			if( is_active_sidebar('sidebar-mobile') ) {
	            echo '<div class="filter d-xl-none"><button class="button-filter-mobile hidden-lg hidden-md" type="submit"><i class="'.besa_get_icon('icon_filter').'" aria-hidden="true"></i>'. esc_html__('Filter', 'besa') .'</button></div>';
	        }
		} 

		public function close_side_woocommerce_show_sidebar_btn() {
			if(is_active_sidebar('sidebar-mobile') ) {
	            echo '<div class="tbay-close-side-mobile"></div>';
	        }
		}


		public function get_title_mobile( $title = '') {
	        if( is_account_page() && is_user_logged_in() ) {
	            $current_user   =  wp_get_current_user();
	            return $current_user->display_name;
	        } elseif ( is_product_tag() ) {
	            $title = esc_html__('Tagged: "', 'besa'). single_tag_title('', false) . '"';
	        }  elseif ( is_product_category() ) {
	            $title = '';
	            $_id = besa_tbay_random_key();
	            $args = array(
	                'id' => 'product-cat-'.$_id,
	                'hide_empty'         => 0,
	                'show_option_none' => '', 
	            );
	            echo '<form method="get" class="woocommerce-fillter">';
	                wc_product_dropdown_categories($args);
	            echo '</form>';

	        } elseif( is_shop () ) {
	            $post_id = wc_get_page_id('shop');
	            if( isset($post_id) && !empty($post_id) ) {
	                $title = get_the_title($post_id);
	            } else {
	                $title = esc_html__('shop','besa');                
	            }
	        }

	        return $title;
		}

		public function the_my_account_avatar() {
			if( is_account_page() && is_user_logged_in() && wp_is_mobile() ) {
	            $current_user   =  wp_get_current_user();
	            $output = '<div class="tbay-my-account-avatar">';
	            $output .= '<div class="tbay-avatar">';
	            $output .= get_avatar( $current_user->user_email, 70, '', $current_user->display_name );
	            $output .= '</div>';
	            $output .= '</div>';

	            echo  trim($output);
	        }
		}

		public function registration_confirm_password_checkout( $checkout_fields  ) {
			if( get_option( 'woocommerce_registration_generate_password' ) !== 'no' || !besa_tbay_get_config('show_confirm_password', false) ) 	return $checkout_fields;

			$checkout_fields['account']['account_confirm_password'] = array(
				'type'              => 'password', 
				'label'             => esc_html__( 'Confirm password', 'besa' ),
				'required'          => true,
				'placeholder'       => _x( 'Confirm Password', 'placeholder', 'besa' )
			);

			return $checkout_fields;
		}

		public function confirm_password_validation( $posted ) {
			$checkout = WC()->checkout;
	      	if ( ! is_user_logged_in() && ( $checkout->must_create_account || ! empty( $posted['createaccount'] ) ) ) {
	          if ( besa_tbay_get_config('show_confirm_password', false) && strcmp( $posted['account_password'], $posted['account_confirm_password'] ) !== 0 ) {
	              wc_add_notice( esc_html__( 'Passwords do not match.', 'besa' ), 'error' );
	          }
	      	}
		}

		public function the_register_form_password_repeat() {
			if( 'yes' === get_option( 'woocommerce_registration_generate_password' ) || ( class_exists('MVX') && is_page_vendor_registration() ) || !besa_tbay_get_config('show_confirm_password', true) )  return;

		    ?>
		    <p class="form-row form-row-wide form-password-repeat">
		      <input type="password" class="input-text" placeholder="<?php esc_attr_e('Confirm Password', 'besa'); ?>" name="password2" id="reg_password2" value="<?php if ( ! empty( $_POST['password2'] ) ) echo esc_attr( $_POST['password2'] ); ?>" />
		    </p>
		    <?php
		}

		public function registration_errors_validation( $reg_errors, $sanitized_user_login, $user_email ) {
			if( !besa_tbay_get_config('show_confirm_password', true) ) return $reg_errors;

		    extract( $_POST );
		    if ( strcmp( $password, $password2 ) !== 0 ) {
		      return new WP_Error( 'registration-error', esc_html__( 'Passwords do not match.', 'besa' ) );
		    }
		    return $reg_errors;
		}

		public function shop_des_image_active($active) {
			$active = besa_tbay_get_config('pro_des_image_product_archives', false);
	
			$active = (isset($_GET['pro_des_image'])) ? (boolean)$_GET['pro_des_image'] : (boolean)$active;
	
			return $active;
		}

		public static function besa_quantity_mini_cart() {
            // Set item key as the hash found in input.qty's name
            $cart_item_key = $_REQUEST['hash'];

            // Get the array of values owned by the product we're updating
            $product_values = WC()->cart->get_cart_item($cart_item_key);

            // Get the quantity of the item in the cart
            $product_quantity = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var($_REQUEST['quantity'], FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);

            // Update cart validation
            $passed_validation  = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $product_values, $product_quantity);    

            // Update the quantity of the item in the cart
            if ($passed_validation) {
                WC()->cart->set_quantity($cart_item_key, $product_quantity, true);
            } 

            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                    )
                ), 
                'cart_hash' => apply_filters('woocommerce_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session())
            );   
        
            wp_send_json($data);   

            die();
        }

	}
endif;

function Besa_WooCommerce() { 
	return Besa_WooCommerce::getInstance();
}

// Global for backwards compatibility.
$GLOBALS['Besa_WooCommerce'] = Besa_WooCommerce();