<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Besa_Elementor_Addons
{
    public function __construct()
    {
        $this->include_control_customize_widgets();
        $this->include_render_customize_widgets();

        add_action('elementor/elements/categories_registered', [$this, 'add_category']);

        add_action('elementor/widgets/register', [$this, 'include_widgets']);

        add_action('wp', [$this, 'regeister_scripts_frontend']);

        // frontend
        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'frontend_after_register_scripts']);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'frontend_after_enqueue_scripts']);

        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_icons'], 99);

        // editor
        add_action('elementor/editor/after_register_scripts', [$this, 'editor_after_register_scripts']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'editor_after_enqueue_scripts']);

        add_action('widgets_init', [$this, 'register_wp_widgets']);
    }

    public function editor_after_register_scripts()
    {
        $suffix = (besa_tbay_get_config('minified_js', false)) ? '.min' : BESA_MIN_JS;

        wp_enqueue_script('waypoints', BESA_SCRIPTS.'/jquery.waypoints'.$suffix.'.js', [], '4.0.0', true);

        // /*slick jquery*/
        wp_register_script('slick', BESA_SCRIPTS.'/slick'.$suffix.'.js', [], '1.0.0', true);
        wp_register_script('besa-custom-slick', BESA_SCRIPTS.'/custom-slick'.$suffix.'.js', [], BESA_THEME_VERSION, true);

        wp_register_script('besa-script', BESA_SCRIPTS.'/functions'.$suffix.'.js', [], BESA_THEME_VERSION, true);

        wp_register_script('popper', BESA_SCRIPTS.'/popper'.$suffix.'.js', [], '1.12.9', true);
        wp_register_script('bootstrap', BESA_SCRIPTS.'/bootstrap'.$suffix.'.js', ['popper'], '4.0.0', true);

        /*Treeview menu*/
        wp_register_script('jquery-treeview', BESA_SCRIPTS.'/jquery.treeview'.$suffix.'.js', [], '1.4.0', true);

        // Add js Sumoselect version 3.0.2
        wp_register_style('sumoselect', BESA_STYLES.'/sumoselect.css', [], '1.0.0', 'all');
        wp_register_script('jquery-sumoselect', BESA_SCRIPTS.'/jquery.sumoselect'.$suffix.'.js', [], '3.0.2', true);
    }

    public function frontend_after_enqueue_scripts()
    {
    }

    public function editor_after_enqueue_scripts()
    {
    }

    public function enqueue_editor_icons()
    {
        wp_enqueue_style('simple-line-icons', BESA_STYLES.'/simple-line-icons.css', [], '2.4.0');
        wp_enqueue_style('font-awesome', BESA_STYLES.'/font-awesome.css', [], '5.10.2');
        wp_enqueue_style('besa-font-tbay-custom', BESA_STYLES.'/font-tbay-custom.css', [], '1.0.0');
        wp_enqueue_style('material-design-iconic-font', BESA_STYLES.'/material-design-iconic-font.css', [], '2.2.0');
    }

    /**
     * @internal Used as a callback
     */
    public function frontend_after_register_scripts()
    {
        $this->editor_after_register_scripts();
    }

    public function register_wp_widgets()
    {
    }

    public function regeister_scripts_frontend()
    {
    }

    public function add_category( $elements_manager )
    {
        $elements_manager->add_category(
            'besa-elements',
            [
                'title' => esc_html__('Besa Elements', 'besa'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * @param $widgets_manager Elementor\Widgets_Manager
     */
    public function include_widgets($widgets_manager)
    {
        $this->include_abstract_widgets($widgets_manager);
        $this->include_general_widgets($widgets_manager);
        $this->include_header_widgets($widgets_manager);
        $this->include_woocommerce_widgets($widgets_manager);
    }

    /**
     * Widgets General Theme.
     */
    public function include_general_widgets($widgets_manager)
    {
        $elements = besa_elementor_general_widgets();

        foreach ($elements as $file) {
            $addon = 'addon_el_'.str_replace('-', '_', $file);
            if (besa_tbay_get_global_config($addon, true)) {
                $path = BESA_ELEMENTOR.'/elements/general/'.$file.'.php';
                if (file_exists($path)) {
                    require_once $path;
                }
            }
        }
    }

    /**
     * Widgets WooComerce Theme.
     */
    public function include_woocommerce_widgets($widgets_manager)
    {
        if (!besa_is_Woocommerce_activated()) {
            return;
        }

        $woo_elements = besa_elementor_woocommerce_widgets();

        foreach ($woo_elements as $file) {
            $addon = 'addon_el_'.str_replace('-', '_', $file);
            if (besa_tbay_get_global_config($addon, true)) {
                $path = BESA_ELEMENTOR.'/elements/woocommerce/'.$file.'.php';
                if (file_exists($path)) {
                    require_once $path;
                }
            }
        }
    }

    /**
     * Widgets Header Theme.
     */
    public function include_header_widgets($widgets_manager)
    {
        $elements = besa_elementor_header_widgets();

        foreach ($elements as $file) {
            $addon = 'addon_el_'.str_replace('-', '_', $file);
            if (besa_tbay_get_global_config($addon, true)) {
                $path = BESA_ELEMENTOR.'/elements/header/'.$file.'.php';
                if (file_exists($path)) {
                    require_once $path;
                }
            }
        }
    }

    /**
     * Widgets Abstract Theme.
     */
    public function include_abstract_widgets($widgets_manager)
    {
        $abstracts = [
            'image',
            'base',
            'responsive',
            'carousel',
        ];

        $abstracts = apply_filters('besa_abstract_elements_array', $abstracts);

        foreach ($abstracts as $file) {
            $path = BESA_ELEMENTOR.'/abstract/'.$file.'.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    public function include_control_customize_widgets()
    {
        $widgets = [
            'sticky-header',
            'column',
            'column-border',
            'section-stretch-row',
            'settings-layout',
        ];

        $widgets = apply_filters('besa_customize_elements_array', $widgets);

        foreach ($widgets as $file) {
            $control = BESA_ELEMENTOR.'/elements/customize/controls/'.$file.'.php';
            if (file_exists($control)) {
                require_once $control;
            }
        }
    }

    public function include_render_customize_widgets()
    {
        $widgets = [
            'sticky-header',
            'column-border',
        ];

        $widgets = apply_filters('besa_customize_elements_array', $widgets);

        foreach ($widgets as $file) {
            $render = BESA_ELEMENTOR.'/elements/customize/render/'.$file.'.php';
            if (file_exists($render)) {
                require_once $render;
            }
        }
    }
}

new Besa_Elementor_Addons();
