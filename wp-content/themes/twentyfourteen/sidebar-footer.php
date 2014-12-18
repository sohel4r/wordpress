<?php
/**
 * The Footer Sidebar
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

if ( ! is_active_sidebar( 'sidebar-3' ) ) {
	return;
}
?>

<<<<<<< HEAD
<div id="supplementary">
=======
<div id="supplementary" class="three">
>>>>>>> 29b1f0fb46bc77e204f748f79127e4863e4c92e9
	<div id="footer-sidebar" class="footer-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
	</div><!-- #footer-sidebar -->
</div><!-- #supplementary -->
