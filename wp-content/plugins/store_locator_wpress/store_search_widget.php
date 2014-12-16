<?php

class Store_wpress_search_widget extends WP_Widget {
	
	//Register widget
	public function __construct() {
		add_action( 'widgets_init', create_function( '', 'register_widget( "store_wpress_search_widget" );' ) );
		parent::__construct(
	 		'store_wpress_search_widget', // Base ID
			'Store Locator Search', // Name
			array( 'description' => 'Stores Search (Store WPress plugin)' ) // Args
		);
	}
	
	//Display
	public function widget( $args, $instance ) {
		extract( $args );
		$title = $instance['title'];
		$locator_url = $instance['locator_url'];
		
		echo $before_widget;
		if ( !empty($title) ) echo $before_title . $title . $after_title;
		
		$d .= '<form action="'.$locator_url.'">';
		$d .= '<input type="text" name="address"><br>';
		$d .= '<input type="submit" value="Search"><br>';
		$d .= '</form>';
		
		echo $d;
		
		echo $after_widget;
	}
	
	//Update
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['locator_url'] = strip_tags( $new_instance['locator_url'] );
		return $instance;
	}
	
	//Form
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
		if ( isset( $instance[ 'locator_url' ] ) ) $locator_url = $instance[ 'locator_url' ];
		?>
		
		<div style="margin-bottom:5px;">
			<label><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</div>
		
		<div style="margin-bottom:5px;">
			<label><?php _e( 'Store locator URL:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'locator_url' ); ?>" name="<?php echo $this->get_field_name( 'locator_url' ); ?>" type="text" value="<?php echo esc_attr( $locator_url ); ?>" />
		</div>
		
		<?php
	}
}

new Store_wpress_search_widget();

?>