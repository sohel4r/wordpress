<<<<<<< HEAD
<?php

	// If uninstall not called from WordPress exit

	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	
		exit();
	
	}
	
	// Delete settings page options from options table
	
	delete_option( 'meteorslides_options' );
	
=======
<?php

	// If uninstall not called from WordPress exit

	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	
		exit();
	
	}
	
	// Delete settings page options from options table
	
	delete_option( 'meteorslides_options' );
	
>>>>>>> 29b1f0fb46bc77e204f748f79127e4863e4c92e9
?>