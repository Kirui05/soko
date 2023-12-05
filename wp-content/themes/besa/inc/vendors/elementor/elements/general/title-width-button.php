<?php

if ( ! defined( 'ABSPATH' ) || function_exists('Besa_Elementor_Title_Width_Button') ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Besa_Elementor_Title_Width_Button extends  Besa_Elementor_Widget_Base{
    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'besa-title-width-button';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Besa Title Width Button', 'besa' );
    }

 
    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-button';
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */

    protected function register_controls() {

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__( 'Title Width Button', 'besa' ),
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'besa'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'besa'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'besa'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'besa'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tbay-element-title-width-button' => 'text-align : {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			'twb_title',
			[
				'label' => esc_html__( 'Title', 'besa' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your title', 'besa' ),
			]
		);

        $this->add_control(
            'twb_text_button',
            [
                'label' => esc_html__( 'Text Button', 'besa' ),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'twb_link_button',
            [
                'label' => esc_html__( 'Link Button', 'besa' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'besa' )
            ]
        );

        $this->end_controls_section();
        $this->register_section_style_twb();
    }

    protected function register_section_style_twb() {
        $this->start_controls_section(
            'section_style_twb',
            [
                'label' => esc_html__('Style', 'besa'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_twb_title_heading',
            [
                'label' => esc_html__('Title', 'besa'),
                'type'   => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'twb_title_typography',
                'selector' => '{{WRAPPER}} .tbay-title',
            ]
        );


        $this->start_controls_tabs('tabs_style_twb_title');

        $this->start_controls_tab(
            'tab_twb_title_normal',
            [
                'label' => esc_html__('Normal', 'besa'),
            ]
        );
        $this->add_control(
            'color_twb_title',
            [
                'label'     => esc_html__('Color', 'besa'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbay-title'    => 'color: {{VALUE}}',
                ],
            ]
        );   

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_twb_title_hover',
            [
                'label' => esc_html__('Hover', 'besa'),
            ]
        );
        $this->add_control(
            'hover_color_twb_title',
            [
                'label'     => esc_html__('Color', 'besa'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbay-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );   
        $this->end_controls_tab();
        $this->end_controls_tabs();


        $this->add_control(
            'style_twb_button_heading',
            [
                'label' => esc_html__('Button', 'besa'),
                'type'   => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'twb_button_typography',
                'selector' => '{{WRAPPER}} .tbay-button',
            ]
        );

        $this->add_control(
            'twb_button_spacing',
            [
                'label'     => esc_html__('Spacing', 'besa'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .tbay-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );   


        $this->start_controls_tabs('tabs_style_twb_button');

        $this->start_controls_tab(
            'tab_twb_button_normal',
            [
                'label' => esc_html__('Normal', 'besa'),
            ]
        );
        $this->add_control(
            'color_twb_button',
            [
                'label'     => esc_html__('Color', 'besa'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbay-button'    => 'color: {{VALUE}}',
                ],
            ]
        );   

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_twb_button_hover',
            [
                'label' => esc_html__('Hover', 'besa'),
            ]
        );
        $this->add_control(
            'hover_color_twb_button',
            [
                'label'     => esc_html__('Color', 'besa'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbay-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );   
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render_item() {
        $settings = $this->get_settings_for_display();
        extract($settings);

        $link = $settings['twb_link_button']['url'];
        $is_external        = $twb_link_button['is_external'];
        $nofollow           = $twb_link_button['nofollow'];
		
        $attribute = '';
        if( $is_external === 'on' ) {
            $attribute .= 'target="_blank"';
        }                

        if( $nofollow === 'on' ) {
            $attribute .= 'rel="nofollow"';
        }


        ?>
            <?php
                if( !empty($twb_title) && isset($twb_title) ) {
                    ?><span class="tbay-title"><?php echo trim($twb_title) ?></span><?php
                }
            ?>
            
            <a href="<?php echo esc_url($link) ?>" <?php echo trim($attribute) ?> class="tbay-btn-theme tbay-button"><?php echo trim($twb_text_button); ?> </a>
        <?php
    }
}
$widgets_manager->register(new Besa_Elementor_Title_Width_Button());
