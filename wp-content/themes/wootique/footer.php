</div><!-- /#wrapper -->
<div class="row">
<div class="fullWidthBar"></div>	
</div>
<div class="container">
<?php global $woo_options; ?>

	<?php
		$total = $woo_options[ 'woo_footer_sidebars' ];
		 if (!isset($total)) $total = 5;
		if ( ( woo_active_sidebar( 'footer-1') ||
			   woo_active_sidebar( 'footer-2') ||
			   woo_active_sidebar( 'footer-3') ||
			   woo_active_sidebar( 'footer-4') ||
			   woo_active_sidebar( 'footer-5') ) && $total > 0 ) :

  	?>
	<div id="footer-widgets" class="row col-<?php echo $total; ?>">
		
		<?php $i = 0; while ( $i <= $total ) : $i++; ?>
			<?php if ( woo_active_sidebar( 'footer-'.$i) ) { ?>

		<div class="block footer-widget-<?php echo $i; ?>">
        	<?php woo_sidebar( 'footer-'.$i); ?>
		</div>

	        <?php } ?>
		<?php endwhile; ?>

		<div class="clearfix"></div>

	</div><!-- /#footer-widgets  -->
    <?php endif; ?>
    
    <?php woo_content_after(); ?>
    
</div><!-- End container -->
<div class="container">
	<div class="row">
		<div class="col-sm-12">
<img class="img-responsive" src="http://demandware.edgesuite.net/aaex_prd/on/demandware.static/Sites-tmlgbp-Site/Sites-tmlgbp-Library/en_GB/v1416486851156/images/footer/footer_cards_new.jpg" alt=""
width="100%">			
		</div>
	</div>
</div>
<br><br>
	<div  class="container">
	  <div class="row">
		<div  class="col-sm-12 copyright">
		<?php if( $woo_options[ 'woo_footer_left' ] == 'true' ) {

				echo stripslashes( $woo_options['woo_footer_left_text'] );

		} else { ?>
			<p><?php bloginfo(); ?> &copy; <?php echo date( 'Y' ); ?>. <?php _e( 'All Rights Reserved.', 'woothemes' ); ?></p>
		<?php } ?>

<?php if ( function_exists( 'has_nav_menu') && has_nav_menu( 'footer-menu' ) ) { ?>


      <?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'footer-nav', 'menu_class' => 'nav navbar-nav', 'theme_location' => 'footer-menu' ) ); ?>

<?php } ?>

		</div>

		<div id="credit" class="col-sm- text-right">
        <?php if( $woo_options[ 'woo_footer_right' ] == 'true' ){

        	echo stripslashes( $woo_options['woo_footer_right_text'] );

		} else { ?>
			<p><?php _e( 'Powered by', 'woothemes' ); ?> <a href="http://www.wordpress.org">WordPress</a>. <?php _e( 'Designed by', 'woothemes' ); ?> <a href="<?php echo ( !empty( $woo_options['woo_footer_aff_link'] ) ? esc_url( $woo_options['woo_footer_aff_link'] ) : 'http://www.woothemes.com' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/woothemes.png" width="74" height="19" alt="Woo Themes" /></a></p>
		<?php } ?>
		</div>
	  </div>
	</div><!-- /#footer  -->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>