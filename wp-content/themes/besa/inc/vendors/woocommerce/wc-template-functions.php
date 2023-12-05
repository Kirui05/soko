<?php

if (!function_exists('besa_tbay_get_products')) {
    function besa_tbay_get_products($categories = [], $product_type = 'featured_product', $paged = 1, $post_per_page = -1, $orderby = '', $order = '', $offset = 0)
    {
        global $woocommerce;
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset,
            'meta_query' => WC()->query->get_meta_query(),
            'tax_query' => WC()->query->get_tax_query(),
        ];

        if (isset($args['orderby'])) {
            if ('price' == $args['orderby']) {
                $args = array_merge($args, [
                    'meta_key' => '_price',
                    'orderby' => 'meta_value_num',
                ]);
            }
            if ('featured' == $args['orderby']) {
                $args = array_merge($args, [
                    'meta_key' => '_featured',
                    'orderby' => 'meta_value',
                ]);
            }
            if ('sku' == $args['orderby']) {
                $args = array_merge($args, [
                    'meta_key' => '_sku',
                    'orderby' => 'meta_value',
                ]);
            }
        }

        if (!empty($categories) && is_array($categories)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $categories,
                    'operator' => 'IN',
                ],
            ];
        }

        switch ($product_type) {
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['ignore_sticky_posts'] = 1;
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'featured_product':
                $args['ignore_sticky_posts'] = 1;
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['tax_query'][] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                        'operator' => 'IN',
                    ],
                ];

                break;
            case 'top_rate':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['meta_query'] = [];
                $args['meta_query'][] = WC()->query->get_meta_query();
                $args['tax_query'][] = WC()->query->get_tax_query();
                break;

            case 'recent_product':
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'random_product':
                $args['orderby'] = 'rand';
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'deals':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $product_ids_on_sale[] = 0;
                $args['post__in'] = $product_ids_on_sale;
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['meta_query'][] = [
                    'relation' => 'AND',
                    [
                        'relation' => 'OR',
                        [
                            'key' => '_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'numeric',
                        ],
                        [
                            'key' => '_min_variation_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'numeric',
                        ],
                    ],
                    [
                        'key' => '_sale_price_dates_to',
                        'value' => time(),
                        'compare' => '>',
                        'type' => 'numeric',
                    ],
                ];
                break;
            case 'on_sale':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $product_ids_on_sale[] = 0;
                $args['post__in'] = $product_ids_on_sale;
                break;
        }

        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $args['meta_query'][] = [
                'relation' => 'AND',
                [
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ],
            ];
        }

        $args['tax_query'][] = [
            'relation' => 'AND',
            [
               'taxonomy' => 'product_visibility',
                'field' => 'slug',
                'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                'operator' => 'NOT IN',
            ],
        ];

        return new WP_Query($args);
    }
}

if (!function_exists('besa_tbay_get_woocommerce_mini_cart')) {
    function besa_tbay_get_woocommerce_mini_cart($args = [])
    {
        $args = wp_parse_args(
            $args,
            [
                'icon_array' => [
                    'has_svg' => false,
                    'iconClass' => ''.besa_get_icon('icon_cart_mobile').'',
                ],
                'show_title_mini_cart' => '',
                'title_mini_cart' => esc_html__('Shopping cart', 'besa'),
                'title_dropdown_mini_cart' => esc_html__('Shopping cart', 'besa'),
                'price_mini_cart' => '',
            ]
        );

        $position = apply_filters('besa_cart_position', 10, 2);

        $mark = '';
        if (!empty($position)) {
            $mark = '-';
        }

        wc_get_template('cart/mini-cart-button'.$mark.$position.'.php', ['args' => $args]);
    }
}

// breadcrumb for woocommerce page
if (!function_exists('besa_tbay_woocommerce_breadcrumb_defaults')) {
    function besa_tbay_woocommerce_breadcrumb_defaults($args)
    {
        global $post;

        $breadcrumb_img = besa_tbay_get_config('woo_breadcrumb_image');
        $breadcrumb_color = besa_tbay_get_config('woo_breadcrumb_color');
        $style = [];
        $img = '';

        if (is_product_category()) {
            $cate = get_queried_object();
            $cateID = $cate->term_id;
        }

        $sidebar_configs = besa_tbay_get_woocommerce_layout_configs();

        $breadcrumbs_layout = besa_tbay_get_config('product_breadcrumb_layout', 'color');

        if (isset($_GET['breadcrumbs_layout'])) {
            $breadcrumbs_layout = $_GET['breadcrumbs_layout'];
        }

        switch ($breadcrumbs_layout) {
            case 'image':
                $breadcrumbs_class = ' breadcrumbs-image';
                break;
            case 'color':
                $breadcrumbs_class = ' breadcrumbs-color';
                break;
            case 'text':
                $breadcrumbs_class = ' breadcrumbs-text';
                break;
            default:
                $breadcrumbs_class = ' breadcrumbs-text';
        }

        if (isset($sidebar_configs['breadscrumb_class'])) {
            $breadcrumbs_class .= ' '.$sidebar_configs['breadscrumb_class'];
        }

        if (!is_page()) {
            $current_page = true;

            switch ($current_page) {
                case is_shop():
                    $page_id = wc_get_page_id('shop');
                    break;
                case is_checkout():
                case is_order_received_page():
                    $page_id = wc_get_page_id('checkout');
                    break;
                case is_edit_account_page():
                case is_add_payment_method_page():
                case is_lost_password_page():
                case is_account_page():
                case is_view_order_page():
                    $page_id = wc_get_page_id('myaccount');
                    break;
                case is_product_category():
                    $page_id = $cateID;
                    break;
                default:
                    $page_id = ( !empty($post->ID) ) ? $post->ID : '';
                    break;
            }
        } else {
            $page_id = $post->ID;
        }

        if (isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) && $breadcrumbs_layout !== 'color' && $breadcrumbs_layout !== 'text') {
            $img = '<img src="'.esc_url($breadcrumb_img['url']).'">';
        }

        if ($breadcrumb_color && $breadcrumbs_layout !== 'image') {
            $style[] = 'background-color:'.$breadcrumb_color;
        }

        $estyle = (!empty($style) && $breadcrumbs_layout !== 'text') ? ' style="'.implode(';', $style).'"' : '';

        $title = $nav = '';

        if ($breadcrumbs_layout == 'image') {
            if (is_product_category()) {
                $title = '<h1 class="page-title">'.single_cat_title('', false).'</h1>';
            } else if( !empty($page_id) ) {
                $title = '<h1 class="page-title">'.get_the_title($page_id).'</h1>';
            }
        } else {
            if (is_single()) {
                $nav = Besa_Single_WooCommerce()->the_product_nav_icon();

                $breadcrumbs_class .= ' active-nav-icon';
            } else {
                if (besa_tbay_get_config('enable_previous_page_woo', true)) {
                    $nav .= '<a href="javascript:history.back()" class="besa-back-btn"><i class="tb-icon tb-icon-arrow-left"></i><span class="text">'.esc_html__('Previous page', 'besa').'</span></a>';
                    $breadcrumbs_class .= ' active-nav-right';
                }
            }
        }

        $args['wrap_before'] = '<section id="tbay-breadscrumb" '.$estyle.' class="tbay-breadscrumb '.esc_attr($breadcrumbs_class).'">'.$img.'<div class="container"><div class="breadscrumb-inner">'.$title.'<ol class="tbay-woocommerce-breadcrumb breadcrumb">';
        $args['wrap_after'] = '</ol>'.$nav.'</div></div></section>';

        return $args;
    }
}

if (!function_exists('besa_tbay_woocommerce_get_cookie_display_mode')) {
    function besa_tbay_woocommerce_get_cookie_display_mode()
    {
        $woo_mode = besa_tbay_get_config('product_display_mode', 'grid');

        if (isset($_COOKIE['besa_display_mode']) && $_COOKIE['besa_display_mode'] == 'grid') {
            $woo_mode = 'grid';
        } elseif (isset($_COOKIE['besa_display_mode']) && $_COOKIE['besa_display_mode'] == 'grid2') {
            $woo_mode = 'grid2';
        } elseif (isset($_COOKIE['besa_display_mode']) && $_COOKIE['besa_display_mode'] == 'list') {
            $woo_mode = 'list';
        }

        return $woo_mode;
    }
}

if (!function_exists('besa_tbay_woocommerce_get_display_mode')) {
    function besa_tbay_woocommerce_get_display_mode()
    {
        $woo_mode = besa_tbay_woocommerce_get_cookie_display_mode();

        if (isset($_GET['display_mode']) && $_GET['display_mode'] == 'grid') {
            $woo_mode = 'grid';
        } elseif (isset($_GET['display_mode']) && $_GET['display_mode'] == 'list') {
            $woo_mode = 'list';
        }

        if (!besa_woo_is_vendor_page() && !is_shop() && !is_product_category() && !is_product_tag()) {
            $woo_mode = 'grid';
        }

        return $woo_mode;
    }
}

/*Check not child categories*/
if (!function_exists('besa_is_check_not_child_categories')) {
    function besa_is_check_not_child_categories()
    {
        global $wp_query;

        if (is_product_category()) {
            $cat = get_queried_object();
            $cat_id = $cat->term_id;

            $args2 = [
                'taxonomy' => 'product_cat',
                'parent' => $cat_id,
            ];

            $sub_cats = get_categories($args2);
            if (!$sub_cats) {
                return true;
            }
        }

        return false;
    }
}

/*Check not product in categories*/
if (!function_exists('besa_is_check_hidden_filter')) {
    function besa_is_check_hidden_filter()
    {
        if (is_product_category()) {
            $checkchild_cat = besa_is_check_not_child_categories();

            if (!$checkchild_cat && 'subcategories' === get_option('woocommerce_category_archive_display')) {
                return true;
            }
        }

        return false;
    }
}

// Two product thumbnail
if (!function_exists('besa_tbay_woocommerce_get_two_product_thumbnail')) {
    function besa_tbay_woocommerce_get_two_product_thumbnail()
    {
        global $post, $product, $woocommerce;

        $size = 'woocommerce_thumbnail';
        $placeholder = wc_get_image_size($size);
        $placeholder_width = $placeholder['width'];
        $placeholder_height = $placeholder['height'];
        $post_thumbnail_id = $product->get_image_id();

        $output = '';
        $class = 'image-no-effect';
        if (has_post_thumbnail()) {
            $attachment_ids = $product->get_gallery_image_ids();

            $class = ($attachment_ids && isset($attachment_ids[0])) ? 'attachment-shop_catalog image-effect' : $class;

            $output .= wp_get_attachment_image($post_thumbnail_id, $size, false, ['class' => $class]);

            if ($attachment_ids && isset($attachment_ids[0])) {
                $output .= wp_get_attachment_image($attachment_ids[0], $size, false, ['class' => 'image-hover']);
            }
        } else {
            $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_html__('Placeholder', 'besa').'" class="'.esc_attr($class).'" width="'.esc_attr($placeholder_width).'" height="'.esc_attr($placeholder_height).'" />';
        }

        return trim($output);
    }
}

// Slider product thumbnail
if (!function_exists('besa_tbay_woocommerce_get_silder_product_thumbnail')) {
    function besa_tbay_woocommerce_get_silder_product_thumbnail()
    {
        global $post, $product, $woocommerce;

        $active = apply_filters('besa_enable_variation_selector', 10, 2);

        wp_enqueue_script('slick');
        wp_enqueue_script('besa-custom-slick');

        $size = 'woocommerce_thumbnail';
        $placeholder = wc_get_image_size($size);
        $placeholder_width = $placeholder['width'];
        $placeholder_height = $placeholder['height'];
        $post_thumbnail_id = $product->get_image_id();

        $output = '';
        $class = 'image-no-effect';

        if (has_post_thumbnail()) {
            $class = 'item-slider';

            $output .= '<div class="tbay-product-slider-gallery">';

            $output .= '<div class="gallery_item first">'.wp_get_attachment_image($post_thumbnail_id, $size, false, ['class' => $class]).'</div>';

            $attachment_ids = $product->get_gallery_image_ids();

            foreach ($attachment_ids as $attachment_id) {
                $output .= '<div class="gallery_item">'.wp_get_attachment_image($attachment_id, $size, false, ['class' => $class]).'</div>';
            }

            $output .= '</div>';
        } else {
            $output .= '<div class="gallery_item first">';

            $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_html__('Placeholder', 'besa').'" class="'.esc_attr($class).'" width="'.esc_attr($placeholder_width).'" height="'.esc_attr($placeholder_height).'" />';
            $output .= '</div>';
        }

        return trim($output);
    }
}

if (!function_exists('besa_product_block_image_class')) {
    function besa_product_block_image_class($class = '')
    {
        $images_mode = apply_filters('besa_woo_display_image_mode', 10, 2);

        if ($images_mode != 'slider') {
            return;
        }
        $class = ' has-slider-gallery';

        echo trim($class);
    }
}

if (!function_exists('besa_slick_carousel_product_block_image_class')) {
    function besa_slick_carousel_product_block_image_class($class = '')
    {
        $images_mode = apply_filters('besa_woo_display_image_mode', 10, 2);

        if ($images_mode != 'slider') {
            return;
        }
        $class = ' slick-has-slider-gallery';

        echo trim($class);
    }
}

if (!function_exists('besa_tbay_product_class')) {
    function besa_tbay_product_class($class = [])
    {
        global $product;

        $class_array = [];

        $type = apply_filters('besa_woo_config_product_layout', 10, 2);
        $class_varible = besa_is_product_variable_sale();

        $class = trim(join(' ', $class));
        if (!is_array($class)) {
            $class = explode(' ', $class);
        }

        array_push($class_array, 'product-block', 'grid', 'product', $type, $class_varible);

        $class_array = array_merge($class_array, $class);

        $class_array = trim(join(' ', $class_array));

        echo 'class="'.esc_attr($class_array).'"';
    }
}

if (!function_exists('besa_has_swatch')) {
    function besa_has_swatch($id, $attr_name, $value)
    {
        $swatches = [];

        $color = $image = $button = '';

        $term = get_term_by('slug', $value, $attr_name);
        if (is_object($term)) {
            $color = sanitize_hex_color(get_term_meta($term->term_id, 'product_attribute_color', true));
            $image = get_term_meta($term->term_id, 'product_attribute_image', true);
            $button = $term->name;
        }

        if ($color != '') {
            $swatches['color'] = $color;
            $swatches['type'] = 'color';
        } elseif ($image != '') {
            $swatches['image'] = $image;
            $swatches['type'] = 'image';
        } else {
            $swatches['button'] = $button;
            $swatches['type'] = 'button';
        }

        return $swatches;
    }
}

if (!function_exists('besa_get_option_variations')) {
    function besa_get_option_variations($attribute_name, $available_variations, $option = false, $product_id = false)
    {
        $swatches_to_show = [];
        foreach ($available_variations as $key => $variation) {
            $option_variation = [];
            $attr_key = 'attribute_'.$attribute_name;
            if (!isset($variation['attributes'][$attr_key])) {
                return;
            }

            $val = $variation['attributes'][$attr_key]; // red green black ..

            if (!empty($variation['image']['thumb_src'])) {
                $option_variation = [
                    'variation_id' => $variation['variation_id'],
                    'image_src' => $variation['image']['thumb_src'],
                    'image_srcset' => $variation['image']['srcset'],
                    'image_sizes' => $variation['image']['sizes'],
                    'is_in_stock' => $variation['is_in_stock'],
                ];
            }

            // Get only one variation by attribute option value
            if ($option) {
                if ($val != $option) {
                    continue;
                } else {
                    return $option_variation;
                }
            } else {
                // Or get all variations with swatches to show by attribute name

                $swatch = besa_has_swatch($product_id, $attribute_name, $val);
                $swatches_to_show[$val] = array_merge($swatch, $option_variation);
            }
        }

        return $swatches_to_show;
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * Show attribute swatches list
 * ------------------------------------------------------------------------------------------------
 */
if (!function_exists('besa_swatches_list')) {
    function besa_swatches_list($attribute_name = false)
    {
        global $product;

        $id = $product->get_id();

        if (empty($id) || !$product->is_type('variable')) {
            return false;
        }

        if (!$attribute_name) {
            $attribute_name = besa_get_swatches_attribute();
        }

        if (empty($attribute_name)) {
            return false;
        }

        $available_variations = $product->get_available_variations();

        if (empty($available_variations)) {
            return false;
        }

        $swatches_to_show = besa_get_option_variations($attribute_name, $available_variations, false, $id);

        if (empty($swatches_to_show)) {
            return false;
        }

        $terms = wc_get_product_terms($product->get_id(), $attribute_name, ['fields' => 'slugs']);

        $swatches_to_show_tmp = $swatches_to_show;

        $swatches_to_show = [];

        foreach ($terms as $id => $slug) {
            if (!empty($swatches_to_show_tmp[$slug])) {
                $swatches_to_show[$slug] = $swatches_to_show_tmp[$slug];
            }
        }

        $out = '';
        $out .= '<div class="tbay-swatches-wrapper"><ul data-attribute_name="attribute_'.$attribute_name.'">';

        foreach ($swatches_to_show as $key => $swatch) {
            $style = $class = '';

            $style .= '';

            $data = '';

            if (isset($swatch['image_src'])) {
                $class .= 'swatch-has-image';
                $data .= 'data-image-src="'.$swatch['image_src'].'"';
                $data .= ' data-image-srcset="'.$swatch['image_srcset'].'"';
                $data .= ' data-image-sizes="'.$swatch['image_sizes'].'"';

                if (!$swatch['is_in_stock']) {
                    $class .= ' variation-out-of-stock';
                }
            }

            $term = get_term_by('slug', $key, $attribute_name);
            $slug = $term->slug;

            $name = '';

            switch ($swatch['type']) {
                case 'color':
                    $style = 'background-color:'.$swatch['color'];
                    $class .= ' variable-item-span-color';
                    break;

                case 'image':
                    $img = wp_get_attachment_image_src($swatch['image'], 'woocommerce_thumbnail');
                    $style = 'background-image: url('.$img['0'].')';
                    $class .= ' variable-item-span-image';
                    break;

                case 'button':
                    $name = $swatch['button'];
                    $class .= ' variable-item-span-label';
                    break;

                default:
                    break;
            }

            $out .= '<li><a href="javascript:void(0)" class="'.$class.' swatch swatch-'.strtolower($slug).'" style="'.esc_attr($style).'" '.$data.'  data-toggle="tooltip" title="'.$name.'">'.$name.'</a></li>';
        }

        $out .= '</ul>';
        $out .= '</div>';

        return $out;
    }
}

if (!function_exists('besa_get_swatches_attribute')) {
    function besa_get_swatches_attribute()
    {
        $custom = get_post_meta(get_the_ID(), '_besa_attribute_select', true);

        return empty($custom) ? besa_tbay_get_config('variation_swatch') : $custom;
    }
}

// get layout configs
if (!function_exists('besa_tbay_get_woocommerce_layout_configs')) {
    function besa_tbay_get_woocommerce_layout_configs()
    {
        if (function_exists('dokan_is_store_page') && dokan_is_store_page()) {
            return;
        }
        if (!is_product()) {
            $page = 'product_archive_sidebar';
        } else {
            $page = 'product_single_sidebar';
        }

        $sidebar = besa_tbay_get_config($page);

        if (!is_singular('product')) {
            $product_archive_layout = (isset($_GET['product_archive_layout'])) ? $_GET['product_archive_layout'] : besa_tbay_get_config('product_archive_layout', 'shop-left');

            if (besa_woo_is_mvx_vendor_store()) {
                $sidebar = 'mvx-sidebar-store';

                if (!is_active_sidebar($sidebar)) {
                    $configs['main'] = ['class' => 'archive-full'];
                }
            }

            if (function_exists('dokan_is_store_page') && dokan_is_store_page() && dokan_get_option('enable_theme_store_sidebar', 'dokan_appearance', 'off') === 'off') {
                $product_archive_layout = 'full-width';
            }

            if (isset($product_archive_layout)) {
                switch ($product_archive_layout) {
                    case 'shop-left':
                        $configs['sidebar'] = ['id' => $sidebar, 'class' => 'col-12 col-xl-3'];
                        $configs['main'] = ['class' => 'col-12 col-xl-9'];
                        break;
                    case 'shop-right':
                        $configs['sidebar'] = ['id' => $sidebar,  'class' => 'col-12 col-xl-3'];
                        $configs['main'] = ['class' => 'col-12 col-xl-9'];
                        break;
                    default:
                        $configs['main'] = ['class' => 'archive-full'];
                        break;
                }

                if (($product_archive_layout === 'shop-left' || $product_archive_layout === 'shop-right') && (empty($configs['sidebar']['id']) || !is_active_sidebar($configs['sidebar']['id']))) {
                    $configs['main'] = ['class' => 'archive-full'];
                }
            }
        } else {
            $product_single_layout = besa_get_single_select_layout();
            $class_main = '';
            $class_sidebar = '';
            if ($product_single_layout == 'left-main' || $product_single_layout == 'main-right') {
                $class_main = 'col-12 col-xl-9';
                $class_sidebar = 'col-12 col-xl-3';

                $sidebar = besa_tbay_get_config('product_single_sidebar', 'product-single');
            }
            if (isset($product_single_layout)) {
                switch ($product_single_layout) {
                    case 'vertical':
                        $configs['main'] = ['class' => 'archive-full'];
                        $configs['thumbnail'] = 'vertical';
                        $configs['breadscrumb'] = 'color';
                        break;
                    case 'horizontal':
                        $configs['main'] = ['class' => 'archive-full'];
                        $configs['thumbnail'] = 'horizontal';
                        $configs['breadscrumb'] = 'color';
                        break;
                    case 'left-main':
                        $configs['sidebar'] = ['id' => $sidebar, 'class' => $class_sidebar];
                        $configs['main'] = ['class' => $class_main];
                        $configs['thumbnail'] = 'horizontal';
                        $configs['breadscrumb'] = 'color';
                        break;
                    case 'main-right':
                        $configs['sidebar'] = ['id' => $sidebar, 'class' => $class_sidebar];
                        $configs['main'] = ['class' => $class_main];
                        $configs['thumbnail'] = 'horizontal';
                        $configs['breadscrumb'] = 'color';
                        break;
                    default:
                        $configs['main'] = ['class' => 'archive-full'];
                        $configs['thumbnail'] = 'horizontal';
                        $configs['breadscrumb'] = 'color';
                        break;
                }

                if (($product_single_layout === 'left-main' || $product_single_layout === 'main-right') && (empty($configs['sidebar']['id']) || !is_active_sidebar($configs['sidebar']['id']))) {
                    $configs['main'] = ['class' => 'archive-full'];
                }
            }
        }

        return $configs;
    }
}

if (!function_exists('besa_class_wrapper_start')) {
    function besa_class_wrapper_start()
    {
        $configs['content'] = 'content';
        $configs['main'] = 'main-wrapper ';

        if (function_exists('dokan_is_store_page') && dokan_is_store_page()) {
            return $configs;
        }

        $sidebar_configs = besa_tbay_get_woocommerce_layout_configs();
        $configs['content'] = besa_add_cssclass($configs['content'], $sidebar_configs['main']['class']);

        if (!is_product()) {
            $configs['content'] = besa_add_cssclass($configs['content'], 'archive-shop');
            $class_main = (isset($_GET['product_archive_layout'])) ? $_GET['product_archive_layout'] : besa_tbay_get_config('product_archive_layout', 'shop-left');

            $configs['main'] = besa_add_cssclass($configs['main'], $class_main);
        } elseif (is_product()) {
            $configs['content'] = besa_add_cssclass($configs['content'], 'singular-shop');

            $class_main = (isset($_GET['product_single_layout'])) ? $_GET['product_single_layout'] : besa_tbay_get_config('product_single_layout', 'horizontal');

            $configs['main'] = besa_add_cssclass($configs['main'], $class_main);
        }

        return $configs;
    }
}

if (!function_exists('besa_woocommerce_meta_query')) {
    function besa_woocommerce_meta_query($type)
    {
        $args = [];
        switch ($type) {
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['order'] = 'DESC';
                $args['orderby'] = 'meta_value_num';
                break;

            case 'featured_product':
                $args['ignore_sticky_posts'] = 1;
                $args['tax_query'][] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                        'operator' => 'IN',
                    ],
                ];
                break;

            case 'top_rate':
                $args['meta_query'] = WC()->query->get_meta_query();
                $args['tax_query'] = WC()->query->get_tax_query();
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;

            case 'recent_product':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                $args['meta_query'] = WC()->query->get_meta_query();
                $args['tax_query'] = WC()->query->get_tax_query();
                break;

            case 'random_product':
                $args['orderby'] = 'rand';
                $args['meta_query'] = [];
                $args['meta_query'][] = WC()->query->get_meta_query();
                break;

            case 'on_sale':
                $args['meta_query'] = WC()->query->get_meta_query();
                $args['tax_query'] = WC()->query->get_tax_query();
                $args['post__in'] = array_merge([0], wc_get_product_ids_on_sale());
                break;
        }

        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $args['meta_query'][] = [
                'relation' => 'AND',
                [
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ],
            ];
        }

        $args['tax_query'][] = [
            'relation' => 'AND',
            [
               'taxonomy' => 'product_visibility',
                'field' => 'slug',
                'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                'operator' => 'NOT IN',
            ],
        ];

        return $args;
    }
}

//Render form fillter product
if (!function_exists('besa_woocommerce_product_fillter')) {
    function besa_woocommerce_product_fillter($options, $name, $default, $class = 'level-0')
    {
        // Only show on product categories
        if (!woocommerce_products_will_display()) :
            return;
        endif; ?>
        <form method="get" class="woocommerce-fillter">
            <span class="sort-title"><?php esc_html_e('Sort by:', 'besa'); ?></span>
            <select name="<?php echo esc_attr($name); ?>" onchange="this.form.submit()" class="select">
                <?php $i = 0;
        foreach ($options as $key => $value) : ?>
                    <?php
                        if ($name === 'product_category') {
                            $category = get_term_by('slug', $key, 'product_cat');
                        }

        if ($name !== 'product_category' || (!empty($category->count) && $category->count > 0)) :
                    ?>
                        <option class="<?php echo (!empty($class[$i])) ? trim($class[$i]) : ''; ?>" value="<?php echo esc_attr($key); ?>" <?php selected($key, besa_woocommerce_get_fillter($name, $default)); ?> ><?php echo trim($value); ?></option>
                        <?php ++$i; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        <?php
            // Keep query string vars intact
            foreach ($_GET as $key => $val) :

                if ($name === $key || 'submit' === $key) :
                    continue;
        endif;
        if (is_array($val)) :
                    foreach ($val as $inner_val) :
                        ?><input type="hidden" name="<?php echo esc_attr($key); ?>[]" value="<?php echo esc_attr($inner_val); ?>" /><?php
                    endforeach; else :
                    ?><input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" /><?php
                endif;
        endforeach; ?>
        </form>
    <?php
    }
}

//get value fillter
if (!function_exists('besa_woocommerce_get_fillter')) {
    function besa_woocommerce_get_fillter($name, $default)
    {
        if (isset($_GET[$name])) :
            return $_GET[$name]; else :
            return $default;
        endif;
    }
}

//Count product of category

if (!function_exists('besa_get_product_count_of_category')) {
    function besa_get_product_count_of_category($cat_id)
    {
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                    'terms' => $cat_id,
                    'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
                ],
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'slug',
                    'terms' => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                    'operator' => 'NOT IN',
                ],
            ],
        ];
        $loop = new WP_Query($args);

        return $loop->found_posts;
    }
}

//Count product of tag

if (!function_exists('besa_get_product_count_of_tags')) {
    function besa_get_product_count_of_tags($tag_id)
    {
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_tag',
                    'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                    'terms' => $tag_id,
                    'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
                ],
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'slug',
                    'terms' => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                    'operator' => 'NOT IN',
                ],
            ],
        ];
        $loop = new WP_Query($args);

        return $loop->found_posts;
    }
}

/*Remove filter*/
if (!function_exists('besa_woocommerce_sub_categories')) {
    function besa_woocommerce_sub_categories($echo = true)
    {
        ob_start();

        wc_set_loop_prop('loop', 0);

        $loop_start = apply_filters('besa_get_woocommerce_sub_categories', ob_get_clean());

        if ($echo) {
            echo trim($loop_start); // WPCS: XSS ok.
        } else {
            return $loop_start;
        }
    }
}

if (!function_exists('besa_is_product_variable_sale')) {
    function besa_is_product_variable_sale()
    {
        global $product;

        $class = '';
        if ($product->is_type('variable') && $product->is_on_sale()) {
            $class = 'tbay-variable-sale';
        }

        return $class;
    }
}

if (!function_exists('besa_woo_content_class')) {
    function besa_woo_content_class($class = '')
    {
        $sidebar_configs = besa_tbay_get_woocommerce_layout_configs();

        if (!(isset($sidebar_configs['right']) && is_active_sidebar($sidebar_configs['right']['sidebar'])) && !(isset($sidebar_configs['left']) && is_active_sidebar($sidebar_configs['left']['sidebar']))) {
            $class .= ' col-12';
        }

        return $class;
    }
}

if (!function_exists('besa_wc_wrapper_class')) {
    function besa_wc_wrapper_class($class = '')
    {
        $content_class = besa_woo_content_class($class);

        return apply_filters('besa_wc_wrapper_class', $content_class);
    }
}

if (!function_exists('besa_find_matching_product_variation')) {
    function besa_find_matching_product_variation($product, $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (strpos($key, 'attribute_') === 0) {
                continue;
            }

            unset($attributes[$key]);
            $attributes[sprintf('attribute_%s', $key)] = $value;
        }

        if (class_exists('WC_Data_Store')) {
            $data_store = WC_Data_Store::load('product');

            return $data_store->find_matching_product_variation($product, $attributes);
        } else {
            return $product->get_matching_variation($attributes);
        }
    }
}

if (!function_exists('besa_get_default_attributes')) {
    function besa_get_default_attributes($product)
    {
        if (method_exists($product, 'get_default_attributes')) {
            return $product->get_default_attributes();
        } else {
            return $product->get_variation_default_attributes();
        }
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * Compare button
 * ------------------------------------------------------------------------------------------------
 */
if (!function_exists('besa_the_yith_compare')) {
    function besa_the_yith_compare($product_id)
    {
        if (class_exists('YITH_Woocompare')) { ?>
            <?php
                $action_add = 'yith-woocompare-add-product';
                $url_args = [
                    'action' => $action_add,
                    'id' => $product_id,
                ];
            ?>
            <div class="yith-compare">
                <a href="<?php echo wp_nonce_url(add_query_arg($url_args), $action_add); ?>" title="<?php esc_attr_e('Compare', 'besa'); ?>" class="compare" data-product_id="<?php echo esc_attr($product_id); ?>">
                    <span><?php echo esc_html__('Compare', 'besa'); ?></span>
                </a>
            </div>
        <?php }
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * WishList button
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_the_yith_wishlist')) {
    function besa_the_yith_wishlist()
    {
        if (!class_exists('YITH_WCWL')) {
            return;
        }

        $enabled_on_loop = 'yes' == get_option('yith_wcwl_show_on_loop', 'no');

        if (!class_exists('YITH_WCWL_Shortcode') || $enabled_on_loop) {
            return;
        }

        $active = besa_tbay_get_config('enable_wishlist_mobile', false);

        $class_mobile = ($active) ? 'shown-mobile' : '';

        echo '<div class="button-wishlist '.esc_attr($class_mobile).'" title="'.esc_attr__('Wishlist', 'besa').'">'.YITH_WCWL_Shortcode::add_to_wishlist([]).'</div>';
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * The Flash Sale
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_tbay_class_flash_sale')) {
    function besa_tbay_class_flash_sale($flash_sales)
    {
        global $product;

        if (isset($flash_sales) && $flash_sales) {
            $class_sale = (!$product->is_on_sale()) ? 'tbay-not-flash-sale' : '';

            return $class_sale;
        }
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * The Item Deal ended Flash Sale
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_tbay_item_deal_ended_flash_sale')) {
    function besa_tbay_item_deal_ended_flash_sale($flash_sales, $end_date)
    {
        global $product;

        $today = strtotime('today');

        if ($today > $end_date) {
            return;
        }

        $output = '';
        if (isset($flash_sales) && $flash_sales && !$product->is_on_sale()) {
            $output .= '<div class="item-deal-ended">';
            $output .= '<span>'.esc_html__('Deal ended', 'besa').'</span>';
            $output .= '</div>';
        }
        echo trim($output);
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * The Count Down Flash Sale
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_tbay_countdown_flash_sale')) {
    function besa_tbay_countdown_flash_sale($time_sale = '', $date_title = '', $date_title_ended = '', $strtotime = false)
    {
        wp_enqueue_script('jquery-countdowntimer');
        $_id = besa_tbay_random_key();

        $today = strtotime('today');

        if ($strtotime) {
            $time_sale = strtotime($time_sale);
        } ?>
        <?php if (!empty($time_sale)) : ?>
            <div class="flash-sales-date">
            <?php if (($today <= $time_sale)): ?>
                    <?php if (isset($date_title) && !empty($date_title)) :  ?>
                        <div class="date-title"><?php echo trim($date_title); ?></div>
                    <?php endif; ?>
                    <div class="time">
                        <div class="tbay-countdown" id="tbay-flash-sale-<?php echo esc_attr($_id); ?>" data-time="timmer"
                             data-date="<?php echo gmdate('m', $time_sale).'-'.gmdate('d', $time_sale).'-'.gmdate('Y', $time_sale).'-'.gmdate('H', $time_sale).'-'.gmdate('i', $time_sale).'-'.gmdate('s', $time_sale); ?>">
                        </div>
                    </div> 
            <?php else: ?>
                <?php if (isset($date_title_ended) && !empty($date_title_ended)) :  ?>
                    <div class="date-title"><?php echo trim($date_title_ended); ?></div>
                <?php endif; ?>
            <?php endif; ?> 
            </div> 
        <?php endif; ?> 
        <?php
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * The Count Down Flash Sale
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_tbay_stock_flash_sale')) {
    function besa_tbay_stock_flash_sale($flash_sales = '')
    {
        global $product;

        if ($flash_sales && $product->get_manage_stock()) : ?>
            <div class="stock-flash-sale stock">
                <?php
                $total_sales = $product->get_total_sales();
        $stock_quantity = $product->get_stock_quantity();

        $total_quantity = (int) $total_sales + (int) $stock_quantity;

        $divi_total_quantity = ($total_quantity !== 0) ? $total_quantity : 1;

        $percentsold = intval(($total_sales / $divi_total_quantity) * 100); ?>
                <div class="progress">
                    <div class="progress-bar active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo esc_attr($percentsold); ?>%">
                    </div>
                </div>
                <span class="tb-sold"><?php echo esc_html__('Sold', 'besa'); ?> : <span class="sold"><?php echo esc_html($total_sales); ?></span><span class="total">/<?php echo esc_html($total_quantity); ?></span></span>
            </div>
        <?php endif;
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * The Total Sold
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_tbay_total_sold')) {
    function besa_tbay_total_sold()
    {
        global $product;
        if (!intval(besa_tbay_get_config('enable_total_sales', true)) || $product->get_type() == 'external') {
            return;
        }
        $skin = besa_tbay_get_theme();
        if ($product->get_manage_stock()) : ?>
            <?php
                $total_sales = $product->get_total_sales(); ?>
            <?php if ($skin === 'style1') {
                    ?><div class="total-sold"><?php echo esc_html($total_sales); ?><?php echo esc_html__(' Sold', 'besa'); ?></div><?php
                } else {
                    ?><div class="total-sold"><span><?php echo esc_html__('Sold: ', 'besa'); ?></span><?php echo esc_html($total_sales); ?></div><?php
                } ?>
            
            
        <?php endif;
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * QuickView button
 * ------------------------------------------------------------------------------------------------
 */
if (!function_exists('besa_the_quick_view')) {
    function besa_the_quick_view($product_id)
    {
        if (besa_tbay_get_config('enable_quickview', true)) {
            wp_enqueue_script('slick');
            wp_enqueue_script('besa-custom-slick'); ?>
            <div class="tbay-quick-view">
                <a href="#" class="qview-button" title ="<?php esc_attr_e('Quick View', 'besa'); ?>" data-effect="mfp-move-from-top" data-product_id="<?php echo esc_attr($product_id); ?>" data-toggle="modal" data-target="#tbay-quickview-modal">
                    <i class="<?php echo besa_get_icon('icon_quick_view'); ?>"></i>
                    <span><?php esc_html_e('Quick View', 'besa'); ?></span>
                </a>
            </div>
            <?php
        }
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * Product name
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('besa_the_product_name')) {
    function besa_the_product_name()
    {
        $active = besa_tbay_get_config('enable_one_name_mobile', false);

        $class_mobile = ($active) ? 'full_name' : '';

        do_action('woocommerce_shop_loop_item_title'); ?>
        
        <h3 class="name <?php echo esc_attr($class_mobile); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php
    }
}

if (!function_exists('besa_woo_is_mvx_vendor_store')) {
    function besa_woo_is_mvx_vendor_store()
    {
        if (!class_exists('MVX')) {
            return false;
        }

        global $MVX;
        if (empty($MVX)) {
            return false;
        }

        if (is_tax($MVX->taxonomy->taxonomy_name)) {
            return true;
        }

        return false;
    }
}

/*
 * Check is vendor page
 *
 * @return bool
 */
if (!function_exists('besa_woo_is_vendor_page')) {
    function besa_woo_is_vendor_page()
    {
        if (function_exists('dokan_is_store_page') && dokan_is_store_page()) {
            return true;
        }

        if (class_exists('WCV_Vendors') && method_exists('WCV_Vendors', 'is_vendor_page')) {
            return WCV_Vendors::is_vendor_page();
        }

        if (besa_woo_is_mvx_vendor_store()) {
            return true;
        }

        if (function_exists('wcfm_is_store_page') && wcfm_is_store_page()) {
            return true;
        }

        return false;
    }
}

if (!function_exists('besa_custom_product_get_rating_html')) {
    function besa_custom_product_get_rating_html($html, $rating, $count)
    {
        global $product;

        $output = '';

        $review_count = $product->get_review_count();

        if (empty($review_count)) {
            $review_count = 0;
        }

        $class = (empty($review_count)) ? 'no-rate' : '';

        $output .= '<div class="rating '.esc_attr($class).'">';
        $output .= $html;
        $output .= '<div class="count"><span>'.$review_count.'</span></div>';
        $output .= '</div>';

        echo trim($output);
    }
}

/*
 * ------------------------------------------------------------------------------------------------
 * Mini cart Button
 * ------------------------------------------------------------------------------------------------
 */
if (!function_exists('besa_tbay_minicart_button')) {
    function besa_tbay_minicart_button($icon, $enable_text, $text, $enable_price)
    {
        global $woocommerce; ?>

        <span class="cart-icon">

            <?php if ($icon['has_svg']) : ?>
                <?php echo trim($icon['svg']); ?>
            <?php else: ?>
                <i class="<?php echo esc_attr($icon['iconClass']); ?>"></i>
            <?php endif; ?>
            <span class="mini-cart-items">
               <?php echo sprintf('%d', $woocommerce->cart->cart_contents_count); ?>
            </span>
        </span>

        <?php if ((($enable_text === 'yes') && !empty($text)) || ($enable_price === 'yes')) { ?>
            <span class="text-cart">

            <?php if (($enable_text === 'yes') && !empty($text)) : ?>
                <span><?php echo trim($text); ?></span>
            <?php endif; ?>

            <?php if ($enable_price === 'yes') : ?>
                <span class="subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            <?php endif; ?>

        </span>

        <?php }
    }
}

/*product time countdown*/
if (!function_exists('besa_woo_product_time_countdown')) {
    function besa_woo_product_time_countdown($countdown = false, $countdown_title = '')
    {
        global $product;

        if (!$countdown) {
            return;
        }

        wp_enqueue_script('jquery-countdowntimer');
        $time_sale = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        $_id = besa_tbay_random_key(); ?>
        <?php if ($time_sale): ?>
            <div class="time">
                <div class="timming">
                    <?php if (isset($countdown_title) && !empty($countdown_title)) :  ?>
                    <div class="date-title"><?php echo trim($countdown_title); ?></div>
                    <?php endif; ?>
                    <div class="tbay-countdown" id="tbay-flash-sale-<?php echo esc_attr($_id); ?>" data-time="timmer" data-date="<?php echo gmdate('m', $time_sale).'-'.gmdate('d', $time_sale).'-'.gmdate('Y', $time_sale).'-'.gmdate('H', $time_sale).'-'.gmdate('i', $time_sale).'-'.gmdate('s', $time_sale); ?>" ></div>
                </div>
            </div> 
        <?php endif; ?> 
        <?php
    }
}
if (!function_exists('besa_woo_product_time_countdown_stock')) {
    function besa_woo_product_time_countdown_stock($countdown = false)
    {
        global $product;
        if (!$countdown) {
            return;
        }

        if ($product->get_manage_stock()) {?>
            <div class="stock">
                <?php
                    $total_sales = $product->get_total_sales();
                    $stock_quantity = $product->get_stock_quantity();

                    if ($stock_quantity >= 0) {
                        $total_quantity = (int) $total_sales + (int) $stock_quantity;
                        $percentsold = intval(($total_sales / $total_quantity) * 100);
                    }
                 ?>
              
                <?php if (isset($percentsold)) { ?>
                    <div class="progress">
                        <div class="progress-bar active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo esc_attr($percentsold); ?>%">
                        </div>
                    </div>
                <?php } ?>
                <?php if ($stock_quantity >= 0) { ?>
                    <span class="tb-sold"><?php echo esc_html__('Sold', 'besa'); ?> : <span class="sold"><?php echo esc_html($total_sales); ?></span><span class="total">/<?php echo esc_html($total_quantity); ?></span></span>
                <?php } ?>
            </div>
        <?php }
    }
}

if (!function_exists('besa_get_single_select_layout')) {
    function besa_get_single_select_layout()
    {
        $custom = get_post_meta(get_the_ID(), '_besa_single_layout_select', true);

        if (isset($_GET['product_single_layout'])) {
            $layout = $_GET['product_single_layout'];
        } else {
            $layout = empty($custom) ? besa_tbay_get_config('product_single_layout') : $custom;
        }

        return apply_filters('besa_get_single_layout', $layout);
    }
}

if (!function_exists('besa_tbay_minicart')) {
    function besa_tbay_minicart()
    {
        $template = apply_filters('besa_tbay_minicart_version', '');
        get_template_part('woocommerce/cart/mini-cart-button', $template);
    }
}

if (!function_exists('besa_tbay_display_custom_tab_builder')) {
    function besa_tbay_display_custom_tab_builder($tabs)
    {
        global $tabs_builder;
        $tabs_builder = true;
        $args = [
      'name' => $tabs,
      'post_type' => 'tbay_customtab',
      'post_status' => 'publish',
      'numberposts' => 1,
    ];

        $tabs = [];

        $posts = get_posts($args);
        foreach ($posts as $post) {
            $tabs['title'] = $post->post_title;
            $tabs['content'] = do_shortcode($post->post_content);

            return $tabs;
        }
        $tabs_builder = false;
    }
}

if (!function_exists('besa_get_product_categories')) {
    function besa_get_product_categories()
    {
        $category = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            ]
        );
        $results = [];
        if (!is_wp_error($category)) {
            foreach ($category as $category) {
                $results[$category->slug] = $category->name.' ('.$category->count.') ';
            }
        }

        return $results;
    }
}

if (!function_exists('besa_get_thumbnail_gallery_item')) {
    function besa_get_thumbnail_gallery_item()
    {
        return apply_filters('besa_get_thumbnail_gallery_item', 'flex-control-nav.flex-control-thumbs li');
    }
}

if (!function_exists('besa_get_gallery_item_class')) {
    function besa_get_gallery_item_class()
    {
        return apply_filters('besa_get_gallery_item_class', 'woocommerce-product-gallery__image');
    }
}

if (!function_exists('besa_video_type_by_url')) {
    /**
     * Retrieve the type of video, by url.
     *
     * @param string $url The video's url
     *
     * @return mixed A string format like this: "type:ID". Return FALSE, if the url isn't a valid video url.
     *
     * @since 1.1.0
     */
    function besa_video_type_by_url($url)
    {
        $parsed = parse_url(esc_url($url));

        switch ($parsed['host']) {
            case 'www.youtube.com':
            case    'youtu.be':
                $id = besa_get_yt_video_id($url);

                return "youtube:$id";

            case 'vimeo.com':
            case 'player.vimeo.com':
                preg_match('/.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/', $url, $matches);
                $id = $matches[5];

                return "vimeo:$id";

            default:
                return apply_filters('besa_woocommerce_featured_video_type', false, $url);
        }
    }
}
if (!function_exists('besa_get_yt_video_id')) {
    /**
     * Retrieve the id video from youtube url.
     *
     * @param string $url The video's url
     *
     * @return string The youtube id video
     *
     * @since 1.1.0
     */
    function besa_get_yt_video_id($url)
    {
        $pattern =
            '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x';
        $result = preg_match($pattern, $url, $matches);
        if (false !== $result) {
            return $matches[1];
        }

        return false;
    }
}

if (!function_exists('besa_get_product_menu_bar')) {
    function besa_get_product_menu_bar()
    {
        $menu_bar = besa_tbay_get_config('enable_sticky_menu_bar', false);

        if (isset($_GET['sticky_menu_bar'])) {
            $menu_bar = $_GET['sticky_menu_bar'];
        }

        return $menu_bar;
    }
    add_filter('besa_woo_product_menu_bar', 'besa_get_product_menu_bar');
}

if (!function_exists('besa_woocommerce_template_single_price_wrapper')) {
    function besa_woocommerce_template_single_price_wrapper()
    {
        global $product;
        if (empty($product->get_price_html()) && $product->get_type() == 'auction') {
            return;
        } ?>
    <div class="price-wrapper">
        <?php
        /**
         * besa_woocommerce_template_single_price_wrapper hook.
         *
         * @hooked woocommerce_template_single_title - 5
         * @hooked woocommerce_template_single_rating - 10
         */
        do_action('besa_woocommerce_template_single_price_wrapper'); ?>
    </div>
    <?php
    }

    add_action('woocommerce_single_product_summary', 'besa_woocommerce_template_single_price_wrapper', 10);
}

/*cart fragments*/
if (!function_exists('besa_added_cart_fragments')) {
    function besa_added_cart_fragments($fragments)
    {
        ob_start();
        $fragments['.mini-cart-items'] = '<span class="mini-cart-items">'.WC()->cart->get_cart_contents_count().'</span>';
        $fragments['.subtotal'] = '<span class="subtotal">'.WC()->cart->get_cart_subtotal().'</span>';

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'besa_added_cart_fragments', 10, 1);

// Remove product in the cart using ajax
if (!function_exists('besa_ajax_product_remove')) {
    function besa_ajax_product_remove()
    {
        // Get mini cart
        ob_start();

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            if ($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key']) {
                WC()->cart->remove_cart_item($cart_item_key);
            }
        }

        WC()->cart->calculate_totals();
        WC()->cart->maybe_set_cart_cookies();

        woocommerce_mini_cart();

        $mini_cart = ob_get_clean();

        // Fragments and mini cart are returned
        $data = [
            'fragments' => apply_filters('woocommerce_add_to_cart_fragments', [
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">'.$mini_cart.'</div>',
                ]
            ),
            'cart_hash' => apply_filters('woocommerce_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session()),
        ];

        wp_send_json($data);

        die();
    }
    add_action('wp_ajax_product_remove', 'besa_ajax_product_remove');
    add_action('wp_ajax_nopriv_product_remove', 'besa_ajax_product_remove');
}

// Quantity mode
if (!function_exists('besa_woocommerce_quantity_mode_active')) {
    function besa_woocommerce_quantity_mode_active()
    {
        $catalog_mode = besa_catalog_mode_active();

        if ($catalog_mode) {
            return false;
        }

        $active = besa_tbay_get_config('enable_woocommerce_quantity_mode', false);

        $active = (isset($_GET['quantity_mode'])) ? $_GET['quantity_mode'] : $active;

        return $active;
    }
}

if (!function_exists('besa_quantity_field_archive')) {
    function besa_quantity_field_archive()
    {
        global $product;
        if ($product && $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock() && !$product->is_sold_individually()) {
            woocommerce_quantity_input(['min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity()]);
        }
    }
}

if (!function_exists('besa_is_quantity_field_archive')) {
    function besa_is_quantity_field_archive()
    {
        global $product;

        if ($product && $product->is_purchasable() && $product->is_in_stock() && !$product->is_sold_individually()) {
            $max_value = $product->get_max_purchase_quantity();
            $min_value = $product->get_min_purchase_quantity();

            if ($max_value && $min_value === $max_value) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('besa_woocommerce_quantity_mode_add_class')) {
    add_filter('woocommerce_post_class', 'besa_woocommerce_quantity_mode_add_class', 10, 2);
    function besa_woocommerce_quantity_mode_add_class($classes, $product)
    {
        if (!besa_woocommerce_quantity_mode_active()) {
            return $classes;
        }

        $classes[] = 'product-quantity-mode';

        return $classes;
    }
}

if (!function_exists('besa_woocommerce_quantity_mode_group_button')) {
    function besa_woocommerce_quantity_mode_group_button()
    {
        if (!besa_woocommerce_quantity_mode_active() || besa_is_woo_variation_swatches_pro()) {
            return;
        }

        global $product;

        if (besa_is_quantity_field_archive() && $product->is_type('simple')) {
            $class_active = 'active';
        } else {
            $class_active = '';
        }

        echo '<div class="quantity-group-btn '.esc_attr($class_active).'">';
        if (besa_is_quantity_field_archive() && $product->is_type('simple')) {
            besa_quantity_field_archive();
        }
        woocommerce_template_loop_add_to_cart();
        echo '</div>';
    }
}

if (!function_exists('besa_woocommerce_add_quantity_mode_list')) {
    function besa_woocommerce_add_quantity_mode_list()
    {
        if (besa_woocommerce_quantity_mode_active()) {
            add_action('besa_woo_list_caption_right', 'besa_woocommerce_quantity_mode_group_button', 20);
        }

        if (besa_is_woo_variation_swatches_pro()) {
            add_action('besa_woo_list_caption_right', 'besa_variation_swatches_pro_group_button', 20);
        }
    }
}

if (!function_exists('besa_woocommerce_quantity_mode_remove_add_to_cart')) {
    function besa_woocommerce_quantity_mode_remove_add_to_cart()
    {
        if (besa_is_woo_variation_swatches_pro() || besa_woocommerce_quantity_mode_active()) {
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        }
    }
    add_action('tbay_woocommerce_before_content_product', 'besa_woocommerce_quantity_mode_remove_add_to_cart', 10);
}

if (!function_exists('besa_woocommerce_cart_item_name')) {
    function besa_woocommerce_cart_item_name($name, $cart_item, $cart_item_key)
    {
        if (!besa_tbay_get_config('show_checkout_image', false) || !is_checkout() || empty($cart_item['data'])) {
            return $name;
        }

        $_product = $cart_item['data'];
        $thumbnail = $_product->get_image('besa_photo_reviews_thumbnail_image');

        $output = $thumbnail;
        $output .= $name;

        return $output;
    }
    add_filter('woocommerce_cart_item_name', 'besa_woocommerce_cart_item_name', 10, 3);
}

if (!function_exists('besa_woocommerce_order_item_name')) {
    function besa_woocommerce_order_item_name($item_name, $item)
    {
        if (!besa_tbay_get_config('show_checkout_image', false) || !is_checkout() || empty($item->get_product()) ) {
            return $item_name;
        }

        $_product = $item->get_product();
        $thumbnail = $_product->get_image('besa_photo_reviews_thumbnail_image');

        $output = $thumbnail;
        $output .= $item_name;

        return $output;
    }
    add_filter('woocommerce_order_item_name', 'besa_woocommerce_order_item_name', 10, 2);
}

/*Hook page checkout multistep*/
if (!function_exists('besa_woocommerce_is_multistep_checkout')) {
    function besa_woocommerce_is_multistep_checkout()
    {
        if (class_exists('YITH_Multistep_Checkout') || class_exists('WPMultiStepCheckout') || class_exists('WooCommerce_Germanized')) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('besa_get_mobile_form_cart_style')) {
    function besa_get_mobile_form_cart_style()
    {
        $ouput = (!empty(besa_tbay_get_config('mobile_form_cart_style', 'default'))) ? besa_tbay_get_config('mobile_form_cart_style', 'default') : 'default';

        return $ouput;
    }
}

if (!function_exists('besa_rocket_lazyload_exclude_class')) {
    function besa_rocket_lazyload_exclude_class($attributes)
    {
        $attributes[] = 'class="attachment-yith-woocompare-image size-yith-woocompare-image"';
        $attributes[] = 'class="logo-mobile-img"';
        $attributes[] = 'class="header-logo-img"';
        $attributes[] = 'class="mobile-infor-img"';
        $attributes[] = 'class="wpml-ls-flag"';
        $attributes[] = 'class="review-images"';

        return $attributes;
    }
    add_filter('rocket_lazyload_excluded_attributes', 'besa_rocket_lazyload_exclude_class');
}

if (!function_exists('besa_get_query_products')) {
    function besa_get_query_products($categories = [], $cat_operator = '', $product_type = 'newest', $limit = '', $orderby = '', $order = '')
    {
        $atts = [
            'limit' => $limit,
            'orderby' => $orderby,
            'order' => $order,
        ];

        if (!empty($categories)) {
            if (!is_array($categories)) {
                $atts['category'] = $categories;
            } else {
                $atts['category'] = implode(', ', $categories);
                $atts['cat_operator'] = $cat_operator;
            }
        }

        $type = 'products';

        $shortcode = new WC_Shortcode_Products($atts, $type);
        $args = $shortcode->get_query_args();

        $args = besa_get_attribute_query_product_type($args, $product_type);

        return new WP_Query($args);
    }
}

if (!function_exists('besa_get_attribute_query_product_type')) {
    function besa_get_attribute_query_product_type($args, $product_type)
    {
        global $woocommerce;

        switch ($product_type) {
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['order'] = 'desc';
                $args['orderby'] = 'meta_value_num';
                $args['ignore_sticky_posts'] = 1;
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;

            case 'featured':
                $args['ignore_sticky_posts'] = 1;
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['tax_query'][] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                        'operator' => 'IN',
                    ],
                ];
                break;

            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;

            case 'newest':
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;

            case 'random_product':
                $args['orderby'] = 'rand';
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;

            case 'deals':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $product_ids_on_sale[] = 0;
                $args['post__in'] = $product_ids_on_sale;
                $args['meta_query'] = [];
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['meta_query'][] = [
                    'relation' => 'AND',
                    [
                        'relation' => 'OR',
                        [
                            'key' => '_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'numeric',
                        ],
                        [
                            'key' => '_min_variation_sale_price',
                            'value' => 0,
                            'compare' => '>',
                            'type' => 'numeric',
                        ],
                    ],
                    [
                        'key' => '_sale_price_dates_to',
                        'value' => time(),
                        'compare' => '>',
                        'type' => 'numeric',
                    ],
                ];
                break;

            case 'on_sale':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $product_ids_on_sale[] = 0;
                $args['post__in'] = $product_ids_on_sale;
                break;
        }

        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $args['meta_query'][] = [
                'relation' => 'AND',
                [
                    'key' => '_stock_status',
                    'value' => 'instock',
                    'compare' => '=',
                ],
            ];
        }

        $args['tax_query'][] = [
            'relation' => 'AND',
            [
               'taxonomy' => 'product_visibility',
                'field' => 'slug',
                'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                'operator' => 'NOT IN',
            ],
        ];

        return $args;
    }
}

/* Ajax Elementor Addon besa Product Tabs **/
if (!function_exists('besa_get_products_tab_ajax')) {
    function besa_get_products_tab_ajax()
    {
        if (!empty($_POST['atts'])) {
            $atts = besa_clean($_POST['atts']);
            $product_type = besa_clean($_POST['value']);
            $atts['product_type'] = $product_type;

            $data = besa_elementor_products_ajax_template($atts);
            echo json_encode($data);
            die();
        }
    }
    add_action('wp_ajax_besa_get_products_tab_shortcode', 'besa_get_products_tab_ajax');
    add_action('wp_ajax_nopriv_besa_get_products_tab_shortcode', 'besa_get_products_tab_ajax');
}

/* Ajax Elementor Addon Product Categories Tabs **/
if (!function_exists('besa_get_products_categories_tab_shortcode')) {
    function besa_get_products_categories_tab_shortcode()
    {
        if (!empty($_POST['atts'])) {
            $atts = besa_clean($_POST['atts']);
            $categories = besa_clean($_POST['value']);
            $atts['categories'] = $categories;

            $data = besa_elementor_products_ajax_template($atts);
            echo json_encode($data);
            die();
        }
    }
    add_action('wp_ajax_besa_get_products_categories_tab_shortcode', 'besa_get_products_categories_tab_shortcode');
    add_action('wp_ajax_nopriv_besa_get_products_categories_tab_shortcode', 'besa_get_products_categories_tab_shortcode');
}

if (!function_exists('besa_elementor_products_ajax_template')) {
    function besa_elementor_products_ajax_template($settings)
    {
        extract($settings);

        $loop = besa_get_query_products($categories, $cat_operator, $product_type, $limit, $orderby, $order);

        if (preg_match('/\\\\/m', $attr_row)) {
            $attr_row = preg_replace('/\\\\/m', '', $attr_row);
        }
        ob_start();

        if ($loop->have_posts()) :

            wc_get_template('layout-products/layout-products.php', ['loop' => $loop, 'product_style' => $product_style, 'attr_row' => $attr_row]);

        endif;

        wc_reset_loop();
        wp_reset_postdata();

        return [
            'html' => ob_get_clean(),
        ];
    }
}

if (!function_exists('besa_show_product_view_counter_on_product_page_style2')) {
    function besa_show_product_view_counter_on_product_page_style2()
    {
        if (!besa_tbay_get_config('enable_views_count', false)) {
            return;
        }

        $count = besa_get_product_view_counter();

        echo '<div class="tbay-visitor-count"><i class="tb-icon tb-icon-zz-eye-fill"></i><span class="counter-value">'.trim($count).esc_html__(' Views', 'besa').'</span></div>';
    }
}

if (!function_exists('besa_show_product_view_counter_on_product_page_style1')) {
    function besa_show_product_view_counter_on_product_page_style1()
    {
        if (!besa_tbay_get_config('enable_views_count', false)) {
            return;
        }

        $count = besa_get_product_view_counter();

        echo '<div class="tbay-visitor-count"><span class="counter-label">'.esc_html__('Views:', 'besa').'</span><span class="counter-value"> '.trim($count).'</span></div>';
    }
}

if (!function_exists('besa_get_product_view_counter')) {
    function besa_get_product_view_counter()
    {
        global $product;
        $id = $product->get_id();
        $meta = get_post_meta($id, 'besa_post_views_count', true);

        if (!$meta) {
            $count = 0;
        } else {
            $count = $meta;
        }

        return $count;
    }
}

if (!function_exists('besa_quantity_variation_swatches_pro_item')) {
    add_action('woocommerce_before_shop_loop_item', 'besa_quantity_variation_swatches_pro_item', 10);
    function besa_quantity_variation_swatches_pro_item()
    {
        if (besa_is_woo_variation_swatches_pro() || besa_woocommerce_quantity_mode_active()) {
            remove_action('besa_woocommerce_group_buttons', 'woocommerce_template_loop_add_to_cart', 10);
        }
    }
}

if ( ! function_exists( 'besa_woocommerce_single_title' ) ) {
    function besa_woocommerce_single_title() {
        the_title( '<h2 class="product_title entry-title">', '</h2>' );
    }
}

/*Fix page search when product_cat emty WOOF  v3.3.4.3*/
if (! function_exists('besa_woo_fix_form_search_cate_empty_woof_new_version')) {
    add_action( 'admin_init', 'besa_woo_fix_form_search_cate_empty_woof_new_version', 10 );
    function besa_woo_fix_form_search_cate_empty_woof_new_version()
    {
        $settings = get_option('woof_settings');

        $settings['force_ext_disable'] = 'by_text,url_request';

        update_option('woof_settings', $settings);
    }
}