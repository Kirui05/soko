<?php
/**
 * Templates Name: Elementor
 * Widget: List Nav
 */

$available_menus = $this->get_available_menus();
if (!$available_menus) {
    return;
}


$settings = $this->get_active_settings();

extract($settings);

?>
<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
	<?php $this->render_element_heading(); ?>
	<?php

    $_id = besa_tbay_random_key();

    wp_nav_menu(array(
        'menu' 			  => $menu,
        'container_class' => 'menu-vertical-container',
        'menu_class' => 'menu-vertical nav',
        'theme_location' => '',
        'fallback_cb' => '',
        'before'          => '',
        'after'           => '',
        'menu_id' => $menu.'-'.$_id,
    ));
    ?>

</div>