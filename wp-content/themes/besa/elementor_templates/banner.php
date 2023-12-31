<?php 
/**
 * Templates Name: Elementor
 * Widget: Banner
 */
extract($settings);
$link               = $banner_link['url'];

if( empty($banner_image) || !is_array($banner_image) ) return;

$this->add_render_attribute('content', 'class', 'banner-content');

?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <div <?php echo $this->get_render_attribute_string('content'); ?>>
        <?php $this->render_item_content($link,$banner_link,$style_link,$style_icon,$style_button,$add_link) ?>
    </div>
    <?php $this->render_item_title($banner_title,$banner_sub_title,$banner_desc); ?>
</div>