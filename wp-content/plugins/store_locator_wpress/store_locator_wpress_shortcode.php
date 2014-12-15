<?php

class Store_locator_wpress_shortcode {
	
	static $js_object;
	
	function add_js_map() {
		//Google Map API
		wp_register_script('gmap_api', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'));
		wp_print_scripts('gmap_api');
		//Store locator
		wp_register_script('store_locator_js', plugin_dir_url( __FILE__ ).'include/js/script.js', array('jquery'));
		wp_print_scripts('store_locator_js');
		//CSS file
		wp_enqueue_style( 'store_locator_css', plugin_dir_url( __FILE__ ).'include/style.css');
	}
	
	function js_wpress_declaration($criteria=array()) {
		$display = $criteria['display'];
		$category_id = $criteria['category_id'];
		$nb_display = $criteria['nb_display'];
		
		if($nb_display=='') $nb_display = $GLOBALS['ygp_store_locator_wpress']['nb_display_search'];
		
		if(self::$js_object!=1) {
			
			if($GLOBALS['ygp_store_locator_wpress']['map_zoom']=='') $GLOBALS['ygp_store_locator_wpress']['map_zoom'] = 13;
			if($GLOBALS['ygp_store_locator_wpress']['map_zoom_detail']=='') $GLOBALS['ygp_store_locator_wpress']['map_zoom_detail'] = 15;
			if($GLOBALS['ygp_store_locator_wpress']['map_type']=='') $GLOBALS['ygp_store_locator_wpress']['map_type'] = 'roadmap';
			
			echo '<script>
			/* <![CDATA[ */
			var Store_wpress = {
				"ajaxurl": "'.admin_url('admin-ajax.php').'", 
				"plugin_url": "'.plugin_dir_url( __FILE__ ).'",
				"display": "'.$display.'", 
				"category_id": "'.$category_id.'",
				"page_number": 1,
				"radius_id": "",
				"current_lat": "", "current_lng": "",
				"searched_lat": "", "searched_lng": "",
				"first_load":"1",
				"widget_nb_display": "",
				"nb_display_search":"'.$nb_display.'",
				"nb_display_default":"'.$GLOBALS['ygp_store_locator_wpress']['nb_display_default'].'",
				"map_type": "'.$GLOBALS['ygp_store_locator_wpress']['map_type'].'",
				"zoom": '.(int)$GLOBALS['ygp_store_locator_wpress']['map_zoom'].',
				"lat": "'.$GLOBALS['ygp_store_locator_wpress']['map_lat'].'", "lng": "'.$GLOBALS['ygp_store_locator_wpress']['map_lng'].'",
				"custom_marker": "'.$GLOBALS['ygp_store_locator_wpress']['custom_marker'].'",
				"closest_stores": "'.$GLOBALS['ygp_store_locator_wpress']['closest_stores'].'",
				"zoom_detail": '.(int)$GLOBALS['ygp_store_locator_wpress']['map_zoom_detail'].'
			};
			/* ]]> */
			</script>';
		}
		
		self::$js_object=1;
	}
	
	function display_stores($atts, $content = null, $code) {
		extract(shortcode_atts(array(
		'display' => '',
		'category_id' => '',
		'nb_display' => '',
		'category_filter' => '',
		'distance_filter' => ''
		), $atts));
		
		if($display=='') $display = 'both';
		
		add_action('wp_footer', array(__CLASS__, 'add_js_map'));
		self::js_wpress_declaration(array('category_id'=>$category_id, 'nb_display'=>$nb_display, 'display'=>$display));
		
		//display store details
		if($_GET['store_id']>0) {
			
			$sdb1 = new Store_locator_wpress_db();
			$stores = $sdb1->return_stores(array('id'=>$_GET['store_id']));
			
			$sb1 = new Store_locator_wpress_display();
			$store_details = $sb1->get_store_details_display($stores);
			$content = $store_details;
			
			$id = $stores[0]['id'];
			$name = $stores[0]['name'];
			$logo = $stores[0]['logo'];
			$address = $stores[0]['address'];
			$url = $stores[0]['url'];
			$marker_icon = $stores[0]['marker_icon'];
			
			//get infowindow display
			$sd = new Store_locator_wpress_display();
			$marker_text = $sd->getMarkerInfowindowDisplay(array('id'=>$id, 'name'=>$name, 'logo'=>$logo, 'address'=>$address, 'url'=>$url));
			
			$GLOBALS['store_wpress_js_on_ready'] = 'init_basic_map(\''.$stores[0]['lat'].'\',\''.$stores[0]['lng'].'\', \''.addslashes($marker_text).'\', \''.$marker_icon.'\');';
		}
		
		//display stores (map or list)
		else {
			
			$GLOBALS['store_wpress_js_on_ready'] .= 'store_locator_load();';
			
			//search box
			$d1 = new Store_locator_wpress_display();
			$content .= $d1->displayAddressSearchBox(array('category_filter'=>$category_filter, 'distance_filter'=>$distance_filter));
			
			//main display containers
			if($display=='list') {
				$content .= self::get_stores_display_list();
			}
			elseif($display=='both') {
				$content .= self::get_stores_display_map_list();
			}
			else {
				$content .= self::get_stores_display_map();
			}
		}
		
		$content = '<p>'.$content.'</p>';
		return $content;
	}
	
	/*
	Start display functions
	*/
	function get_stores_display_map() {
		$width = $GLOBALS['ygp_store_locator_wpress']['map_width'];
		$height = $GLOBALS['ygp_store_locator_wpress']['map_height'];
		
		$d1 = new Store_locator_wpress_display();
		
		$content .= $d1->displayPaginationBox();
		$content .= '<div id="map" style="overflow: hidden; width:'.$width.'; height:'.$height.';"></div>';
		return $content;
	}
	
	function get_stores_display_list() {
		
		$d1 = new Store_locator_wpress_display();
		
		$content .= $d1->displayPaginationBox();
		$content .= '<div id="store_locator_list"></div>';
		return $content;
	}

	function get_stores_display_map_list() {
		$width = $GLOBALS['ygp_store_locator_wpress']['map_width'];
		$height = $GLOBALS['ygp_store_locator_wpress']['map_height'];
		
		$d1 = new Store_locator_wpress_display();
		
		$content .= $d1->displayPaginationBox();
		$content .= '<div id="map" style="overflow: hidden; width:'.$width.'; height:'.$height.';"></div>';
		$content .= '<br>';
		$content .= '<div id="store_locator_list"></div>';
		$content .= '<div id="previousNextButtons2"></div>';
		return $content;
	}
}

?>