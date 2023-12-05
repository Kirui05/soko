<?php

if ( !function_exists('besa_column_child_border')) {
    function besa_column_child_border( $widget ) {

        $widget->start_controls_section(
            'element_child_border',
            array(
                'label' => esc_html__( 'Element border child', 'besa' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
 
        $widget->add_control(
            'enable_element_child_border',
            array(
                'label'                 =>  esc_html__( 'Enable element border child', 'besa' ),
                'type'                  => \Elementor\Controls_Manager::SWITCHER,
                'default'               => '',
                'return_value'          => 'yes',
                'prefix_class'          => 'enable-element-child-border-'
            )
        );

        $widget->add_control(
            'element_child_border_line_height',
            [
                'label' => esc_html__( 'Height', 'besa' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 44,
                ],
                'selectors' => [
                    '{{WRAPPER}}.enable-element-child-border-yes .elementor-element,
                    {{WRAPPER}} .tbay-custom-language, {{WRAPPER}} .tbay-currency,{{WRAPPER}} .tbay-element .tbay-login > a,
                    {{WRAPPER}} .column-element-child-border .elementor-element' => 'height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tbay-currency,{{WRAPPER}} .tbay-custom-language' => 'line-height: {{SIZE}}{{UNIT}}'
				],
                'condition' => [
                    'enable_element_child_border' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'element_child_border_color',
            [
                'label' => esc_html__( 'Border Color', 'besa' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.enable-element-child-border-yes .elementor-element::after,
                    {{WRAPPER}} .column-element-child-border .elementor-element::after' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'enable_element_child_border' => 'yes',
                ],
            ]
        );
        
        $widget->add_control(
            'element_child_border_width',
            [
                'label' => esc_html__( 'Width', 'besa' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}}.enable-element-child-border-yes .elementor-element::after,
                    {{WRAPPER}} .column-element-child-border .elementor-element::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_element_child_border' => 'yes',
                ],
            ]
        );
        
        $widget->add_control(
            'element_child_border_height',
            [
                'label' => esc_html__( 'Height', 'besa' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}}.enable-element-child-border-yes .elementor-element::after,
                    {{WRAPPER}} .column-element-child-border .elementor-element::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_element_child_border' => 'yes',
                ],
            ]
        );
        
        $widget->add_control(
            'element_child_border_margin',
            [
                'label' => esc_html__( 'Margin', 'besa' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}}.enable-element-child-border-yes .elementor-element::after,
                    {{WRAPPER}} .column-element-child-border .elementor-element::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}} .tbay-custom-language .sub-menu,{{WRAPPER}} .woocommerce-currency-switcher-form .SumoSelect > .optWrapper' => 'left: calc(-{{RIGHT}}{{UNIT}} + -1px)'
                ],
                'condition' => [
                    'enable_element_child_border' => 'yes',
                ],
            ]
        );

        $widget->end_controls_section(); 

    }

    add_action( 'elementor/element/column/section_style/before_section_start', 'besa_column_child_border', 10, 1 );
}

