<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

?>
<div class="images">

	<?php


			echo apply_filters( 'woocommerce_single_product_image_html', get_post_meta( $post->ID, 'image_url', true ) );



	?>
	
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
