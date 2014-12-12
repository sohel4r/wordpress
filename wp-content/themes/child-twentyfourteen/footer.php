<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

</div><!-- #main -->
</div><!-- #PAGE -->
<div id="footer_bg">
<footer id="colophon" role="contentinfo">
<?php get_sidebar( 'footer' ); ?>
<div id="site-generator">
	<div id="footer_contents">
		<?php 

		add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
		add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
		add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
		function my_css_attributes_filter($var) {
		  return is_array($var) ? array() : '';
		}
		$defaults = array(
			'theme_location'  => 'footer',
			'container'       => false,
			'menu_class'      => 'footer',
			'menu_id'         => 'footer',
			'echo'            => true,
			'fallback_cb'     => false,
			'before'          => '',
			'after'           => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		);

		wp_nav_menu( $defaults );

		 ?>
					 	  <p class="copy">
						<?php do_action( 'twentyfourteen_credits' ); ?>
						<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentyfourteen' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentyfourteen' ), 'WordPress' ); ?></a>
					 	  </p>
						</div>
					</div>
			</footer><!-- #colophon -->
</div>

	<?php wp_footer(); ?>

</body>
</html>
