<?php
class My_Walker extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '<br /><span class="sub">' . $item->description . '</span>';
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}



function sButton($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#'), $atts));
   return '<a class="button" href="'.$link.'"><span>' . do_shortcode($content) . '</span></a>';
}
add_shortcode('button', 'sButton');

function theme_name_scripts() {
	wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );


function home_top_widget_child(){
$args = array(
	'name'          => 'Top Page Bar',
	'id'            => 'ch_page_bg',
	'description'   => '',
    'class'         => 'hfeed',
	'before_widget' => '<div id="ch_page_bg" ><div class="hfeed"><div id="ch_page">',
	'after_widget'  => '</div></div></div>' );	
register_sidebar( $args );

register_sidebar( array(
	'name'          => 'Footer First',
	'id'            => 'footer_widget1',
	'before_widget' => '<aside id="nav_menu-1" class="widget widget_nav_menu">',
	'after_widget'  => '</aside>'
	) );
register_sidebar( array(
	'name'          => 'Footer Second',
	'id'            => 'footer_widget2',
	'before_widget' => '<aside id="nav_menu-2" class="widget widget_nav_menu">',
	'after_widget'  => '</aside>'	
	) );
register_sidebar( array(
	'name'          => 'Footer Third',
	'id'            => 'footer_widget3',
	'before_widget' => '<aside id="nav_menu-3" class="widget widget_nav_menu">',
	'after_widget'  => '</aside>'	
	) );
}
add_action( 'widgets_init', 'home_top_widget_child' );



