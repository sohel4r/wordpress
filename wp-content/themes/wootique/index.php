<?php
	get_header();
	global $woo_options;
?>
<div class="row">
	<div id="featured-products" class="col-sm-12 <?php if ( get_option( 'woo_featured_product_style' ) == 'slider' ) { echo 'fp-slider'; } ?>">
		

		<div class="row">
<?php
$args = array( 'post_type' => 'product', 'posts_per_page' => get_option( 'woo_featured_product_limit' ), 'meta_key' => '_featured', 'meta_value' => 'yes' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post(); $_product;
if ( function_exists( 'get_product' ) ) {
	$_product = get_product( $loop->post->ID );
} else {
	$_product = new WC_Product( $loop->post->ID );
}
?>			
			<div class="col-sm-4 featured-products">


					<div class="front">

						<?php woocommerce_show_product_sale_flash( $post, $_product ); ?>

							<a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
							<?php if ( has_post_thumbnail( $loop->post->ID ) ) echo get_the_post_thumbnail( $loop->post->ID, 'shop_thumbnail' ); else echo '<img src="' . $woocommerce->plugin_url() . '/assets/images/placeholder.png" alt="Placeholder" class="img-responsive" />'; ?>
							</a>

					</div><!--/.front-->

					<div class="back">
						<h3><a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">	<?php the_title(); ?></a></h3>
						<span class="price"><?php echo $_product->get_price_html(); ?></span>
						<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
					</div><!--/.back-->

			</div>
	
			<?php endwhile; ?>
		</div>
		<div class="clear"></div>
	</div><!--/#featured-products-->
</div><!--End row Feature content-->
<div class="clearfix"></div>
<div class="col-sm-12">
	<div class="col-sm-4"></div>
	<div class="col-sm-2 text-center">Featured</div>
	<div class="col-sm-4"></div>
</div>
    <div id="content">
		<div class="row">
		<div id="main" class="col-sm-12">

			<div class="product-gallery">
				<h2><?php _e( 'Recent Products', 'woothemes' ); ?></h2>
				<?php echo do_shortcode( '[recent_products per_page="4" columns="1"]' ); ?>
			</div><!--/.product-gallery-->

		</div><!-- /#main -->
		
        <?php get_sidebar(); ?>
		</div><!--End row -->
    </div><!-- /#content -->

<?php get_footer(); ?>