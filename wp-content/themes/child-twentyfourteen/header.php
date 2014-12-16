<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<link rel="stylesheet" href="<?= dirname( get_bloginfo('stylesheet_url') ); ?>/tablet.css">
	<link rel="stylesheet" href="<?= dirname( get_bloginfo('stylesheet_url') ); ?>/smart.css">
	<script src="<?= dirname( get_bloginfo('stylesheet_url') ); ?>/js/pup.js"></script>
	<script src="<?= dirname( get_bloginfo('stylesheet_url') ); ?>/js/viewer.js"></script>	
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
	<header id="branding" role="banner">
			<nav id="access" role="navigation">
				<h3 class="assistive-text">MENU</h3>
<hgroup>
			<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
<h2 id="site-title"><span>
		<?php if ( get_header_image() ) : ?>
		<a id="home" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
		<?php endif; ?></span></h2>
</hgroup>
				<div class="skip-link"><a class="assistive-text" href="#content" title="メインコンテンツへ移動">メインコンテンツへ移動</a></div>
				<div class="skip-link"><a class="assistive-text" href="#secondary" title="サブコンテンツへ移動">サブコンテンツへ移動</a></div>
<?php 
$walker = new My_Walker;
$defaults = array(
	'theme_location'  => 'primary',
	'menu'            => '',
	'container'       => 'div',
	'container_class' => 'menu-main-container',
	'container_id'    => '',
	'menu_class'      => 'menu',
	'menu_id'         => 'menu-main',
	'echo'            => true,
	'fallback_cb'     => false,
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => $walker
);

wp_nav_menu( $defaults );

 ?>
			</nav><!-- #access -->

<div id="header_right">

<div id="header_right_bottom">
<?php 
		add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
		add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
		add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
		function my_css_attributes_filter($var) {
		  return is_array($var) ? array() : '';
		}
$social = array(
	'theme_location'  => 'social',
	'menu'            => '',
	'container'       => 'div',
	'container_class' => 'menu-social',
	'container_id'    => 'header_social',
	'menu_class'      => 'none-social',
	'menu_id'         => 'none-social',
	'echo'            => true,
	'fallback_cb'     => false,
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => ''
);

wp_nav_menu( $social );

 ?>
</div>
</div><!-- #header_right-->
	</header><!-- #branding -->

<?php dynamic_sidebar('ch_page_bg'); ?>
<div id="main">
