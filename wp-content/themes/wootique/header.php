<?php
/**
 * Header Template
 *
 * Here we setup all logic and HTML that is required for the header section of all screens.
 *
 */
 global $woo_options, $woocommerce;
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">

<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>

<!-- CSS  -->
  


<!-- /CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<!-- The main stylesheet -->
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css">
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php $GLOBALS['feedurl'] = get_option('woo_feed_url'); if ( !empty($feedurl) ) { echo $feedurl; } else { echo get_bloginfo_rss('rss2_url'); } ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
      



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<?php wp_head(); ?>
<?php woo_head(); ?>
  </head>
  <body>
    <div class="head">
        <div class="container">
          <div class="row">
            <div class="col-sm-3">
              <div class="logo">
<?php 
  if ($woo_options['woo_texttitle'] != 'true' ) : 
  $logo = $woo_options['woo_logo']; 
  if ( is_ssl() ) { $logo = preg_replace("/^http:/", "https:", $woo_options['woo_logo']); }
?>
  <h1>
    <a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'description' ); ?>">
      <img src="<?php if ($logo) echo $logo; else { echo get_template_directory_uri(); ?>/images/logo.png<?php } ?>"  alt="<?php bloginfo( 'name' ); ?>" />
    </a>
  </h1>
<?php endif; ?>              
              </div>
            </div>
            <div class="col-sm-3"></div>
            <div class="col-sm-4">
              <div class="row">
                <div class="top-menu">
<?php if ( function_exists( 'has_nav_menu') && has_nav_menu( 'top-menu' ) ) { ?>


      <?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav navbar-nav', 'theme_location' => 'top-menu' ) ); ?>

<?php } ?>
                </div>
              </div>
              <div class="row">
<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">

  <div class="input-group">
    <input type="text" class="form-control" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e( 'Search for products', 'woocommerce' ); ?>" />
      <span class="input-group-btn">
        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
      </span>
  </div>      
    <input type="hidden" name="post_type" value="product" />

</form>
              </div>
            </div><!--End top Menu -->
            <div class="col-sm-2 cart-pro">
<span>Your shopping bag</span><br>
<span class="glyphicon glyphicon-shopping-cart"></span>
<?php woo_nav_after(); ?>
            </div>
          </div>
        </div>
        <div class="row main-nav">
          <nav class="container">
<?php
if ( function_exists( 'has_nav_menu') && has_nav_menu( 'primary-menu') ) {
  wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav navbar-nav', 'theme_location' => 'primary-menu' ) );
} else {
?>
<ul id="main-nav" class="nav navbar-nav">
  <?php
  if ( isset($woo_options[ 'woo_custom_nav_menu' ]) AND $woo_options[ 'woo_custom_nav_menu' ] == 'true' ) {
    if ( function_exists( 'woo_custom_navigation_output') )
      woo_custom_navigation_output();
  } else { ?>
        <?php if ( is_page() ) $highlight = "page_item"; else $highlight = "page_item current_page_item"; ?>
        <li class="<?php echo $highlight; ?>"><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'woothemes' ) ?></a></li>
        <?php
      wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' );
  }
  ?>
</ul><!-- /#nav -->
<?php } ?>  
          </nav>
        </div>
    </div>
<div class="container">
<div class="topMargin"></div>