<?php 

$text_domain               = esc_html__(' comments','besa');    
if( get_comments_number() == 1) {
    $text_domain = esc_html__(' comment','besa');
}
$skin = besa_tbay_get_theme(); 
?>
<div class="post item-post single-reladted">   
    <figure class="entry-thumb <?php echo  (!has_post_thumbnail() ? 'no-thumb' : ''); ?>">
        <a href="<?php the_permalink(); ?>"  class="entry-image">
            <?php if($skin === 'style1') {
                the_post_thumbnail( array(100, 100) ); 
            } else {
                the_post_thumbnail();
            };?>
        </a> 
    </figure>
    <div class="entry-header">

        <?php if ( get_the_title() ) : ?>
            <h3 class="entry-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <?php if($skin === 'style1') {
                    besa_post_meta_comment(); 
                }
                ?>
            </h3>
        <?php endif; ?>
        <ul class="entry-meta-list">
            <li class="entry-date"><i class="<?php echo besa_get_icon('icon_date_blog'); ?>"></i><?php echo besa_time_link(); ?></li>
        </ul>

    </div>
</div>
