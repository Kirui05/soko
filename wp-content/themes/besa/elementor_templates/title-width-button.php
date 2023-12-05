<?php 
/**
 * Templates Name: Elementor
 * Widget: Button
 */
if( empty($settings['twb_text_button']) && empty($settings['twb_title']) ) return;

$this->add_render_attribute('content', 'class', 'banner-content');
?>
<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php $this->render_item(); ?>
</div>