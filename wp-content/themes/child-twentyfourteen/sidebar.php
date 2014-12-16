<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<div id="secondary" class="widget-area" role="complementary">


	<?php if ( has_nav_menu( 'secondary' ) ) : ?>
		<?php wp_nav_menu( array( 'theme_location' => 'secondary' ) ); ?>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php endif; ?>
</div><!-- #secondary -->
