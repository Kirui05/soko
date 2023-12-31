<?php
if ( !function_exists( 'besa_tbay_page_metaboxes' ) ) {
    function besa_tbay_page_metaboxes() {
        $sidebars = besa_sidebars_array();

        $footers = array_merge( array('global' => esc_html__( 'Global Setting', 'besa' )), besa_tbay_get_footer_layouts() );
        $headers = array_merge( array('global' => esc_html__( 'Global Setting', 'besa' )), besa_tbay_get_header_layouts() );


		$prefix = 'tbay_page_';

        $cmb2 = new_cmb2_box( array(
            'id'           => $prefix . 'display_setting',
            'title'        => esc_html__( 'Display Settings', 'besa' ),
            'object_types' => array( 'page' ), // Post type
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true, 
        ) );

        $cmb2->add_field( array(
            'name' => esc_html__( 'Select Layout', 'besa' ),
            'id'   => $prefix.'layout',
            'type' => 'select',
            'options' => array(
                'main' => esc_html__('Main Content Only', 'besa'),
                'left-main' => esc_html__('Left Sidebar - Main Content', 'besa'),
                'main-right' => esc_html__('Main Content - Right Sidebar', 'besa'),
            )
        ) );

        $cmb2->add_field( array(
            'id' => $prefix.'left_sidebar',
            'type' => 'select',
            'name' => esc_html__('Left Sidebar', 'besa'),
            'options' => $sidebars
        ) );

        $cmb2->add_field( array(
            'id' => $prefix.'right_sidebar',
            'type' => 'select',
            'name' => esc_html__('Right Sidebar', 'besa'),
            'options' => $sidebars
        ) );

        
        $cmb2->add_field( array(
            'id' => $prefix.'show_breadcrumb',
            'type' => 'select',
            'name' => esc_html__('Show Breadcrumb?', 'besa'),
            'options' => array(
                'no' => esc_html__('No', 'besa'),
                'yes' => esc_html__('Yes', 'besa')
            ),
            'default' => 'yes',
        ) );

        $cmb2->add_field( array(
            'name' => esc_html__( 'Select Breadcrumbs Layout', 'besa' ),
            'id'   => $prefix.'breadcrumbs_layout',
            'type' => 'select',
            'options' => array(
                'image' => esc_html__('Background Image', 'besa'),
                'color' => esc_html__('Background color', 'besa'),
                'text' => esc_html__('Just text', 'besa')
            ),
            'default' => 'text',
        ) );

        $cmb2->add_field( array(
            'id' => $prefix.'breadcrumb_color',
            'type' => 'colorpicker',
            'name' => esc_html__('Breadcrumb Background Color', 'besa')
        ) );

        $cmb2->add_field( array(
            'id' => $prefix.'breadcrumb_image',
            'type' => 'file',
            'name' => esc_html__('Breadcrumb Background Image', 'besa')
        ) );

        
        $cmb2->add_field( array(
            'id' => $prefix.'header_type',
            'type' => 'select', 
            'name' => esc_html__('Header Layout Type', 'besa'),
            'description' => esc_html__('Choose a header for your website.', 'besa'),
            'options' => $headers,
            'default' => 'global'
        ) );

        $cmb2->add_field( array(
            'id' => $prefix.'footer_type',
            'type' => 'select',
            'name' => esc_html__('Footer Layout Type', 'besa'),
            'description' => esc_html__('Choose a footer for your website.', 'besa'),
            'options' => $footers,
            'default' => 'global'
        ) );

        $cmb2->add_field( array(
            'id' => $prefix.'extra_class',
            'type' => 'text',
            'name' => esc_html__('Extra Class', 'besa'),
            'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'besa')
        ) );

    }
    add_action( 'cmb2_admin_init', 'besa_tbay_page_metaboxes', 10 );
}

if ( !function_exists( 'besa_tbay_cmb2_style' ) ) {
	function besa_tbay_cmb2_style() {
		wp_enqueue_style( 'besa-cmb2', BESA_THEME_DIR . '/inc/vendors/cmb2/assets/cmb2.css', array(), '1.0' );
	}
    add_action( 'admin_enqueue_scripts', 'besa_tbay_cmb2_style' );
}


