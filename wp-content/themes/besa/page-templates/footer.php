<?php

$footer 	= apply_filters( 'besa_tbay_get_footer_layout', 'footer_default' );

?>

	</div><!-- .site-content -->
	<?php if ( besa_tbay_active_newsletter_sidebar() ) : ?>
		<div id="newsletter-popup" class="newsletter-popup">
			<?php dynamic_sidebar( 'newsletter-popup' ); ?> 
		</div>
	<?php endif; ?>
	
	<?php
		$class_mobile_collapse = besa_tbay_get_config('mobile_footer_collapse', false) ? 'footer-mobile-collapse' : '';
	?>

	<footer id="tbay-footer" class="tbay-footer <?php echo (!empty($footer)) ? esc_attr($footer) : ''; ?> <?php echo trim($class_mobile_collapse); ?> ">
		<?php if ( $footer != 'footer_default' ): ?>
			
			<?php besa_tbay_display_footer_builder($footer); ?>

		<?php else: ?> 
			
			<?php get_template_part( 'page-templates/footer-default' ); ?>
			
		<?php endif; ?>			
	</footer><!-- .site-footer -->

	<?php
		/**
		* besa_after_do_footer hook
		*
		* @hooked besa_after_do_footer - 10
		*/
		do_action('besa_after_do_footer');
	?>

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>