<?php 
/**
 * Templates Name: Elementor
 * Widget: Post Grid
 */
extract($settings);

if( empty($username) ) {

    echo esc_html__('Please enter username', 'besa');

    return;
}

if( empty($limit) ) {
    echo esc_html__('Please enter number of photos', 'besa');

    return;
}

$_id = besa_tbay_random_key();
$this->settings_layout();

$this->add_render_attribute('item', 'class', 'item');
 
$media_array = tbay_elementor_scrape_instagram( $username );

?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php $this->render_element_heading(); ?>



    <?php 
 
                $time       = (!empty($show_time)) ? 'true' : 'false';
                $like       = (!empty($show_like)) ? 'true' : 'false';
                $comment    = (!empty($show_comment)) ? 'true' : 'false';

                $this->add_render_attribute(
                    'row',
                    [
                        'id' => 'instagram-feed'. $_id,
                        'class' => [ 'instagram-feed' ],
                        'data-number' => $limit,
                        'data-username' => $username,
                        'data-image_size' => $photo_size,
                        'data-id' => '#instagram-feed'. $_id,
                        'data-time_ago' => $time,
                        'data-like' => $like,
                        'data-comment' => $comment
                    ]
                );

                if($settings['layout_type'] === 'carousel') { 
                    $this->add_render_attribute('row', 'class', 'slick-instagram' );
                }
            ?>

            <div <?php echo $this->get_render_attribute_string('row'); ?>></div>

    <?php $this->render_button(); ?>
</div>