<?php if ( has_nav_menu( 'primary' ) ) : ?>
    <nav data-duration="400" class="hidden-xs hidden-sm tbay-megamenu menu slide animate navbar tbay-horizontal-default">
    <?php

        $menu = '';
        $tbay_location = 'primary';

        $locations  = get_nav_menu_locations();
            if ( !empty( $locations[ $tbay_location ] )) {
                $menu_id    = $locations[ $tbay_location ] ;
                $menu_obj   = wp_get_nav_menu_object( $menu_id );
            }
            $args['theme_location']     = $tbay_location;

        if ( !empty($menu_obj) ) {
            $menu = besa_get_transliterate($menu_obj->slug);
        }

        $args = array(
            'theme_location' => $tbay_location,
            'menu_class' => 'nav navbar-nav megamenu',
            'fallback_cb' => '',
            'menu_id' => 'primary-menu',
			'walker' => new besa_Tbay_Nav_Menu(),
            'items_wrap'  => '<ul id="%1$s" class="%2$s" data-id="'. $menu .'">%3$s</ul>'
        );
        wp_nav_menu($args);
    ?>
    </nav>
<?php endif; ?>