<?php
if( ! class_exists('Locatoraid_Searchform_Widget23') )
{
class Locatoraid_Searchform_Widget23 extends WP_Widget
{
	var $app = '';
	var $w_arg = array(
		'category'=> 0,
		);
	
	public function __construct()
	{
		$this->dir = dirname(__FILE__) . '/..';
		$this->app = 'locatoraid';
		parent::__construct(
	 		'locatoraid_widget', // Base ID
			'Locatoraid Search Form', // Name
			array( 'description' => __( 'Show your locator search form', 'text_domain' ), ) // Args
		);
	}

 	public function form( $instance )
	{
		$instance = wp_parse_args( (array) $instance, $this->w_arg );
		$return = $this->render( 'admin', array('instance' => $instance) );
		echo $return;
	}

	public function widget( $args, $instance )
	{
		/* find the front page */
		global $wpdb;
		$shortcode = '[' . $this->app . '';
		$pages = array();
		$pages = $wpdb->get_results( 
			"
			SELECT 
				ID 
			FROM $wpdb->posts 
			WHERE 
				(post_type = 'post' OR post_type = 'page') AND 
				(
				post_content LIKE '%" . $shortcode . "%]%'
				)
			"
			);
		if( ! $pages )
		{
			return;
		}

		$locator_page = get_permalink($pages[0]->ID);
		$label = (isset($instance['label'])) ? $instance['label'] : __('Address or Zip Code', $this->app);
		$btn = (isset($instance['btn'])) ? $instance['btn'] : __('Search', $this->app);

		$params = array(
			'locator_page'	=> $locator_page,
			'label'			=> $label,
			'btn'			=> $btn,
			);
		$return = $this->render( 'front', $params );
		echo $return;
	}

	public function render( $view, $vars = array() )
	{
		$file = $this->dir . '/widgets/views/searchform/' . $view . '.php';
		if( ! file_exists($file) )
		{
			$content = 'File "' . $view . '" does not exist<br>';
		}
		else
		{
			extract( $vars );
			ob_start();
			require( $file );
			$content = ob_get_contents();
			ob_end_clean();
		}
		return $content;
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "Locatoraid_Searchform_Widget23" );' ) );
}