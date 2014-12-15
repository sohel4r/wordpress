<?php

class Store_closest_stores_widget extends WP_Widget {
	
	//Register widget
	public function __construct() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "Store_closest_stores_widget" );' ) );
		parent::__construct(
	 		'Store_closest_stores_widget', // Base ID
			'Closest Stores', // Name
			array( 'description' => 'Closest Stores (Store WPress plugin)' ) // Args
		);
	}
	
	//Display
	public function widget( $args, $instance ) {
		extract( $args );
		$title = $instance['title'];
		$nb_stores = $instance['nb_stores'];
		
		echo $before_widget;
		if ( !empty($title) ) echo $before_title . $title . $after_title;
		
		//include the JS files
		add_action('wp_footer', array('Store_locator_wpress_shortcode', 'add_js_map'));
		$s1 = new Store_locator_wpress_shortcode();
		$s1->js_wpress_declaration();
		
		//execute on dom ready
		$GLOBALS['store_wpress_js_on_ready'] .= 'Store_wpress.widget_nb_display="'.$nb_stores.'"; display_widget_closest_stores();';
		
		$d .= '<p id="widget_store_locator_list"></p>';
		
		echo $d;
		
		echo $after_widget;
	}
	
	//Update
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['nb_stores'] = strip_tags( $new_instance['nb_stores'] );
		return $instance;
	}
	
	//Form
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
		if ( isset( $instance[ 'nb_stores' ] ) ) $nb_stores = $instance[ 'nb_stores' ];
		?>
		
		<div style="margin-bottom:5px;">
			<label><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</div>
		
		<div style="margin-bottom:5px;">
			<label><?php _e( 'Number of stores:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'nb_stores' ); ?>" name="<?php echo $this->get_field_name( 'nb_stores' ); ?>" type="text" value="<?php echo esc_attr( $nb_stores ); ?>" />
		</div>
		
		<?php
	}
}

new Store_closest_stores_widget();

?>