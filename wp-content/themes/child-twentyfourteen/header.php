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
<div id="header_social">
<ul>
<li><a href="https://twitter.com/PupJp" target="_blank"><img src="http://www.p-up.jp/img/icon4.png" alt="Twitter"></a></li>
<li><a href="https://www.facebook.com/Pup.jp" target="_blank"><img src="http://www.p-up.jp/img/icon3.png" alt="Facebook"></a></li>
<li><a href="https://www.p-up.jp/inquiry"><img src="http://www.p-up.jp/img/icon2.png" alt="お問い合わせフォーム"></a></li>
<li><a href="http://www.p-up.jp/sitemap"><img src="http://www.p-up.jp/img/icon1.png" alt="サイトマップ"></a></li>
</ul>
</div></div>
</div><!-- #header_right-->
	</header><!-- #branding -->
<div id="main">

