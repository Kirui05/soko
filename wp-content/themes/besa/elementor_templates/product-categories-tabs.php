<?php 
/**
 * Templates Name: Elementor
 * Widget: Product Categories Tabs
 */

extract( $settings );

if( empty($categories) ) return;

$random_id = besa_tbay_random_key();

$this->settings_layout();

if( $ajax_tabs === 'yes' ) {
    $this->add_render_attribute('wrapper', 'class', 'ajax-active'); 
}
?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php 
        $this->render_tabs_title($categories, $random_id);
        $this->render_product_tabs_content($categories, $random_id);
    ?>
</div>