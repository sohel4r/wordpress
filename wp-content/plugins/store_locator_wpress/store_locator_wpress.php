<?php
/*
Plugin Name: Store Locator for WordPress
Plugin URI: http://yougapi.com/products/wp/store_locator/
Description: Integrate an Advanced and fully featured Store Locator into your WordPress
Version: 2.0
Author: Yougapi Technology LLC
Author URI: http://yougapi.com
*/

$GLOBALS['ygp_store_locator_wpress'] = get_option('ygp_store_locator_wpress');
$GLOBALS['ygp_store_locator_wpress']['item_name'] = 'store_wpress';

require_once dirname( __FILE__ ).'/activation.php';
include_once dirname( __FILE__ ).'/admin/options.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_admin.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_db.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_shortcode.php';
require_once dirname( __FILE__ ).'/store_locator_wpress_display.php';
require_once dirname( __FILE__ ).'/store_closest_stores_widget.php';
require_once dirname( __FILE__ ).'/store_search_widget.php';

$GLOBALS['store_locator_lang']['store'] = 'store';
$GLOBALS['store_locator_lang']['stores'] = 'stores';
$GLOBALS['store_locator_lang']['store_name'] = 'Store name';
$GLOBALS['store_locator_lang']['address'] = 'Address';
$GLOBALS['store_locator_lang']['url'] = 'Url';
$GLOBALS['store_locator_lang']['tel'] = 'Tel';
$GLOBALS['store_locator_lang']['email'] = 'Email';
$GLOBALS['store_locator_lang']['description'] = 'Description';
$GLOBALS['store_locator_lang']['more_information'] = 'More information';
$GLOBALS['store_locator_lang']['related_post'] = 'Related post';
$GLOBALS['store_locator_lang']['more_details'] = 'More details';
$GLOBALS['store_locator_lang']['get_directions'] = 'Get directions';
$GLOBALS['store_locator_lang']['to_here'] = 'To here';
$GLOBALS['store_locator_lang']['from_here'] = 'From here';
$GLOBALS['store_locator_lang']['streetview'] = 'Streetview';
$GLOBALS['store_locator_lang']['view_all_stores'] = 'View all stores';
$GLOBALS['store_locator_lang']['next'] = 'Next';
$GLOBALS['store_locator_lang']['previous'] = 'Previous';
$GLOBALS['store_locator_lang']['search_by_address'] = 'Search by address';
$GLOBALS['store_locator_lang']['search'] = 'Search';
$GLOBALS['store_locator_lang']['distance'] = 'Distance';
$GLOBALS['store_locator_lang']['category'] = 'Category';
$GLOBALS['store_locator_lang']['all_categories'] = 'All categories';

$GLOBALS['store_locator_settings']['distance'] = array('1', '5', '25', '50', '100', '600');


class Store_locator_wpress {
	
	function Store_locator_wpress() {
		add_action('plugins_loaded', array(__CLASS__, 'add_scripts'));
		add_action('wp_footer', array(__CLASS__, 'add_onload'));
		
		//AJAX
		add_action( 'wp_ajax_nopriv_store_wpress_listener', array(__CLASS__, 'store_wpress_listener') );
		add_action( 'wp_ajax_store_wpress_listener', array(__CLASS__, 'store_wpress_listener') );
		
		//Shortcode
		add_shortcode( 'store_wpress', array('Store_locator_wpress_shortcode', 'display_stores') );
				
		if(is_admin()) {
			register_activation_hook(__FILE__, array(__CLASS__, 'on_plugin_activation'));
			//Settings link
			add_filter( 'plugin_action_links', array(__CLASS__, 'plugin_action_links'), 121, 2);

		}
	}
	
	function add_onload() {
	    ?>
	    <script type="text/javascript">
	    my_onload_callback = function() {
	    	<?php echo $GLOBALS['store_wpress_js_on_ready']; ?>
	    };
		
	    if( typeof jQuery == "function" ) { 
	        jQuery(my_onload_callback); // document.ready
	    }
	    else {
	        document.getElementsByTagName('body')[0].onload = my_onload_callback; // body.onload
	    }
	    
	    </script>
	    <?php
	}
	
	function add_scripts() {
		if (!is_admin()) {
			
		}
	}
	
	//AJAX calls
	function store_wpress_listener() {
		
		$method = $_POST['method'];
		if(is_multisite()) $method=1;
		
		//display stores Map
		if($method=='display_map') {
			$lat = $_POST['lat'];
			$lng = $_POST['lng'];
			$page_number = $_POST['page_number'];
			$category_id = $_POST['category_id'];
			$category2_id = $_POST['category2_id'];
			$radius_id = $_POST['radius_id'];
			$nb_display = $_POST['nb_display'];
			
			$sdb1 = new Store_locator_wpress_db();
			$ss1 = new Store_locator_wpress_shortcode();
			
			if($nb_display=='') $nb_display = $GLOBALS['ygp_store_locator_wpress']['nb_display_search'];
			$distance_unit = $GLOBALS['ygp_store_locator_wpress']['distance_unit'];
			
			if($page_number=='') $page_number = 1; //default value just in case
			if($nb_display=='') $nb_display = 20; //default value just in case
			
			$locations =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng, 'page_number'=>$page_number, 'nb_display'=>$nb_display, 
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			
			//calculate number total of stores
			$stores2 =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng,
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			$nb_stores = count($stores2);
			
			//previous/next buttons
			$d1 = new Store_locator_wpress_display();
			$previousNextButtons = $d1->displayPreviousNextButtons($page_number, $nb_stores, $nb_display);
			
			if($nb_stores==1) $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['store'];
			else $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['stores'];
			
			$results['title'] = $title;
			$results['previousNextButtons'] = $previousNextButtons;
			$results['locations'] = $locations;
			$results['markersContent'] = $d1->displayMarkersContent($locations);
			$results = json_encode($results);
			
			echo $results;
			exit;
		}
		
		//display stores list
		else if($method=='display_list') {
			$page_number = $_POST['page_number'];
			$lat = $_POST['lat'];
			$lng = $_POST['lng'];
			$category_id = $_POST['category_id'];
			$category2_id = $_POST['category2_id'];
			$radius_id = $_POST['radius_id'];
			$nb_display = $_POST['nb_display'];
			$no_info_links = $_POST['no_info_links']; //activate or no the links display
			$widget_display = $_POST['widget_display'];
			$display_type = $_POST['display_type'];
			
			$sdb1 = new Store_locator_wpress_db();
			$ss1 = new Store_locator_wpress_shortcode();
			
			if($nb_display=='') $nb_display = $GLOBALS['ygp_store_locator_wpress']['nb_display_search'];
			$distance_unit = $GLOBALS['ygp_store_locator_wpress']['distance_unit'];
			
			if($page_number=='') $page_number = 1; //default value just in case
			if($nb_display=='') $nb_display = 20; //default value just in case
			
			$stores =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng, 'page_number'=>$page_number, 'nb_display'=>$nb_display, 
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			
			//calculate number total of stores
			$stores2 =  $sdb1->get_locations(array('lat'=>$lat, 'lng'=>$lng, 
			'distance_unit'=>$distance_unit, 'category_id'=>$category_id, 'category2_id'=>$category2_id, 'radius_id'=>$radius_id));
			$nb_stores = count($stores2);
			
			//previous/next buttons
			$d1 = new Store_locator_wpress_display();
			$previousNextButtons = $d1->displayPreviousNextButtons($page_number, $nb_stores, $nb_display);
			
			if($lat!=''&&$lng!='') $distance_display=1;
			
			$sd1 = new Store_locator_wpress_display();
			if($nb_stores>0) {
				if($display_type=='both') $content = $sd1->display_stores_list($stores, array('distance_display'=>$distance_display, 'no_info_links'=>$no_info_links, 'widget_display'=>$widget_display));
				else $content = $sd1->display_stores_list($stores, array('distance_display'=>$distance_display, 'no_info_links'=>$no_info_links, 'widget_display'=>$widget_display));
			}
			else $content='';
			
			if($nb_stores==1) $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['store'];
			else $title = $nb_stores.' '.$GLOBALS['store_locator_lang']['stores'];
			
			$results['title'] = $title;
			$results['previousNextButtons'] = $previousNextButtons;
			$results['stores'] = $content;
			$results = json_encode($results);
			
			echo $results;
			exit;
		}
	}
	
	function plugin_action_links($links, $file) {
		if ( $file == plugin_basename( dirname(__FILE__).'/store_locator_wpress.php' ) ) {
			$links[] = '<a href="plugins.php?page=store_locator_wpress">Settings</a>';
		}
		return $links;
	}
	
	function on_plugin_activation() {
		if(self::notify_verification() && !is_multisite() ) {
			//create the plugin table if it doesn't exist
			$sdb1 = new Store_locator_wpress_db();
			$sdb1->setup_tables();	
		}
	}
	
	function notify_verification() {
		$url = 'http://yougapi.com/updates/?item=locator_wpress&s='.site_url();
		wp_remote_get($url);
		return 1;
	}
}

new Store_locator_wpress();

?>