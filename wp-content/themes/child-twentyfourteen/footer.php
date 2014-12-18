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
<<<<<<< HEAD
<?php get_sidebar( 'footer' ); ?>
=======
<div id="supplementary" class="three">
	<div id="first" class="widget-area" role="complementary">
		<?php dynamic_sidebar('footer_widget1'); ?>
	</div>
	<div id="second" class="widget-area" role="complementary">
		<?php dynamic_sidebar('footer_widget2'); ?>
	</div>
	<div id="third" class="widget-area" role="complementary">
		<?php dynamic_sidebar('footer_widget3'); ?>
	</div>		
</div>
>>>>>>> 29b1f0fb46bc77e204f748f79127e4863e4c92e9
<div id="site-generator">
	<div id="footer_contents">
		<?php 


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
