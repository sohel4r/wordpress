<?php
/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/*-----------------------------------------------------------------------------------*/
/* Any WooCommerce overrides and functions can be found here
/*-----------------------------------------------------------------------------------*/


// Add html5 shim
add_action('wp_head', 'wootique_html5_shim');
function wootique_html5_shim() {
?>
<!-- Load Google HTML5 shim to provide support for <IE9 -->
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php
}

// Disable WooCommerce styles
if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
	// WooCommerce 2.1 or above is active
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
	// WooCommerce is less than 2.1
	define( 'WOOCOMMERCE_USE_CSS', false );
}

/*-----------------------------------------------------------------------------------*/
/* Header
/*-----------------------------------------------------------------------------------*/

// Hook in the search
add_action('woo_nav_before', 'wootique_header_search');
function wootique_header_search() {
	global $woo_options;
	?>
	<div id="search-top">

    	<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url(); ?>">
			<label class="screen-reader-text" for="s"><?php _e('Search for:', 'woothemes'); ?></label>
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s"  class="field s" placeholder="<?php _e('Search for products', 'woothemes'); ?>" />
			<input type="image" class="submit btn" name="submit" value="<?php _e('Search', 'woothemes'); ?>" src="<?php echo get_template_directory_uri(); ?>/images/ico-search.png">
			<?php if ($woo_options['woo_header_search_scope'] == 'products' ) { echo '<input type="hidden" name="post_type" value="product" />'; } else { echo '<input type="hidden" name="post_type" value="post" />'; } ?>
		</form>
		<div class="fix"></div>

	</div><!-- /.search-top -->
	<?php
}

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Adjust markup on all WooCommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'woostore_before_content', 10);
add_action('woocommerce_after_main_content', 'woostore_after_content', 20);

// Fix the layout etc
function woostore_before_content() {
	?>
	<!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">

        <!-- #main Starts -->
        <?php woo_main_before(); ?>
        <div id="main" class="col-left">
    <?php
}
function woostore_after_content() {
	?>
		<?php if ( is_search() && is_post_type_archive() ) { add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 ); } ?>
		<?php woo_pagenav(); ?>
		</div><!-- /#main -->
        <?php woo_main_after(); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
    <?php
}

function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '&post_type=product';
	return $settings;
} // End woocommerceframework_add_search_fragment()

// Add the WC sidebar in the right place
add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10);

// Remove breadcrumb (we're using the WooFramework default breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
add_action( 'woocommerce_before_main_content', 'woostore_breadcrumb', 01, 0);

function woostore_breadcrumb() {
	global  $woo_options;
	if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) {
		woo_breadcrumbs();
	}
}

// Remove pagination (we're using the WooFramework default pagination)
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 ); // <2.0
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 ); // 2.0+


// Adjust the star rating in the sidebar
add_filter('woocommerce_star_rating_size_sidebar', 'woostore_star_sidebar');

function woostore_star_sidebar() {
	return 12;
}

// Adjust the star rating in the recent reviews
add_filter('woocommerce_star_rating_size_recent_reviews', 'woostore_star_reviews');

function woostore_star_reviews() {
	return 12;
}

// Change columns in product loop to 3
add_filter('loop_shop_columns', 'woostore_loop_columns');

function woostore_loop_columns() {
	return 3;
}

// Change columns in related products output to 3

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

if ( ! function_exists('woocommerce_output_related_products') && version_compare( WOOCOMMERCE_VERSION, "2.1" ) < 0 ) {
	function woocommerce_output_related_products() {
		    woocommerce_related_products( 3,3 );
	}
}

add_filter( 'woocommerce_output_related_products_args', 'wootique_related_products' );
function wootique_related_products() {
	$args = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);
	return $args;
}

if ( ! function_exists( 'woo_upsell_display' ) ) {
	function woo_upsell_display() {
	    // Display up sells in correct layout.
	    woocommerce_upsell_display( -1, 3 );
	}
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woo_upsell_display', 15 );

// If theme lightbox is enabled, disable the WooCommerce lightbox and make product images prettyPhoto galleries
/*add_action( 'wp_footer', 'woocommerce_prettyphoto' );
function woocommerce_prettyphoto() {
	global $woo_options;
	if ( $woo_options[ 'woo_enable_lightbox' ] == "true" ) {
		update_option( 'woocommerce_enable_lightbox', false );
		?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.images a').attr('rel', 'prettyPhoto[product-gallery]');
				});
			</script>
		<?php
	}
}*/

// Move the price below the excerpt on the single product page
remove_action( 'woocommerce_template_single_summary', 'woocommerce_template_single_price', 10, 2);
add_action( 'woocommerce_template_single_summary', 'woocommerce_template_single_price', 25, 2);

// Display 12 products per page
add_filter('loop_shop_per_page', create_function('$cols', 'return 12;'));

add_action('woo_nav_after', 'woocommerce_cart_link', 10);
add_action('woo_nav_after', 'wootique_checkout_button', 10);

function woocommerce_cart_link() {
	global $woocommerce;
	?>
    	<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="'<?php _e( 'View your shopping cart', 'woothemes' ); ?>'" class="cart-button">
			<span><?php echo sprintf(_n( '%d item &ndash; ', '%d items &ndash; ', $woocommerce->cart->get_cart_contents_count(), 'woothemes'), $woocommerce->cart->get_cart_contents_count()) . $woocommerce->cart->get_cart_total(); ?></span>
		</a>
	<?php
}

function wootique_checkout_button() {
global $woocommerce;
?>
    <?php
	    if (sizeof($woocommerce->cart->cart_contents)>0) :
	    echo '<a href="'.$woocommerce->cart->get_checkout_url().'" class="checkout-link">'.__('Checkout','woothemes').'</a>';
		endif;
	?>
<?php
}

add_filter('add_to_cart_fragments', 'header_add_to_cart_fragment');
function header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	woocommerce_cart_link();

	$fragments['.cart-button'] = ob_get_clean();

	return $fragments;

}
