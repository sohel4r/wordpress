<?php

class Store_locator_wpress_display {
	
	function Store_locator_wpress_display() {
		add_filter('the_content', array(__CLASS__, 'display_linked_store'), 11);
	}
	
	function display_linked_store($content) {
		
		$post_id = get_the_ID();
		
		$sdb1 = new Store_locator_wpress_db();
		$stores = $sdb1->return_stores(array('post_id'=>$post_id));
		$stores = self::display_stores_list($stores, array('no_info_links'=>1));
		
		$content = $content.'<p>'.$stores.'</p>';
		
		return $content;
	}
	
	function get_store_details_display($stores) {
		$width = $GLOBALS['ygp_store_locator_wpress']['map_width_detail'];
		$height = $GLOBALS['ygp_store_locator_wpress']['map_height_detail'];
		$current_url = get_permalink();
		
		$streetview_thumbnail = 'http://cbk0.google.com/cbk?output=thumbnail&w=316&h=208&ll='.$stores[0]['lat'].','.$stores[0]['lng'];
		
		$display .= '<div id="map" style="overflow: hidden; width:'.$width.'; height:'.$height.'; max-width: none;"></div><br>';
		
		if($stores[0]['logo']!='') $display .= '<img src="'.$stores[0]['logo'].'" style="padding-bottom:10px; padding-top:10px;"><br>';
		
		$display .= '<b>'.$GLOBALS['store_locator_lang']['store_name'].':</b> '.$stores[0]['name'].' <small>(<a href="'.$current_url.'">'.$GLOBALS['store_locator_lang']['view_all_stores'].'</a>)</small><br>';
		$display .= '<b>'.$GLOBALS['store_locator_lang']['address'].':</b> '.$stores[0]['address'].'<br>';
		
		//if($streetview=='on') $display .= '<div><img src="'.$streetview_thumbnail.'" style="overflow: hidden; width:'.$width.'; height:'.$height.'"></div>';
		
		if($stores[0]['url']!='') $display .= '<b>'.$GLOBALS['store_locator_lang']['url'].':</b> <a href="'.$stores[0]['url'].'" target="_blank">'.$stores[0]['url'].'</a><br>';
		if($stores[0]['tel']!='') $display .= '<b>'.$GLOBALS['store_locator_lang']['tel'].':</b> '.$stores[0]['tel'].'<br>';
		if($stores[0]['email']!='') $display .= '<b>'.$GLOBALS['store_locator_lang']['email'].':</b> '.$stores[0]['email'].'<br>';
		if($stores[0]['description']!='') $display .= '<br><b>'.$GLOBALS['store_locator_lang']['description'].'</b><br>'.$stores[0]['description'].'<br>';
		
		return $display;
	}
	
	function display_stores_list($stores,$criteria=array()) {
		$no_info_links = $criteria['no_info_links']; //display info links or no
		$distance_display = $criteria['distance_display']; //display distance or no
		$widget_display = $criteria['widget_display'];
		
		$current_url = get_permalink();
		
		for($i=0; $i<count($stores); $i++) {
			
			$map_url = 'http://maps.google.com/maps/api/staticmap?center='.$stores[$i]['lat'].','.$stores[$i]['lng'].'&zoom=15&size=160x90&markers=color:red|'.$stores[$i]['lat'].','.$stores[$i]['lng'].'&sensor=false';
			
			if(count($stores)>1) $content .= '<div style="padding-bottom:10px; border-bottom: 1px solid #e7e7e7; overflow:hidden;">';
			else $content .= '<div style="padding-bottom:10px; overflow:hidden;">';
			$content .= '<img src="'.$map_url.'" style="float:left; margin-right:25px; margin-bottom:5px;">';
			
			$content .= '<a href="'.$GLOBALS['ygp_store_locator_wpress']['locator_url'].'?store_id='.$stores[$i]['id'].'"><b>'.$stores[$i]['name'].'</b></a>';
			
			if($distance_display) $content .= ' (<font color="red">'.number_format($stores[$i]['distance'],1).' '.$GLOBALS['ygp_store_locator_wpress']['distance_unit'].'</font>)';
			$content .= '<br>';
			$content .= $stores[$i]['address'].'';
			
			//more info links
			if($no_info_links!=1) {
				$content .= '<br><small><a href="'.$current_url.'?store_id='.$stores[$i]['id'].'">'.$GLOBALS['store_locator_lang']['more_information'].'</a>';
				if($stores[$i]['post_id']>0) {
					$post_url = get_permalink($stores[$i]['post_id']);
					$content .= ' - <a href="'.$post_url.'">'.$GLOBALS['store_locator_lang']['related_post'].'</a>';
				}
				$content .= '</small>';
			}
			
			$content .= '</div>';
			$content .= '<br>';
		}
		return $content;
	}
	
	/*
	function display_stores_list2($stores,$criteria=array()) {
		$no_info_links = $criteria['no_info_links']; //display info links or no
		$distance_display = $criteria['distance_display']; //display distance or no
		$widget_display = $criteria['widget_display'];
				
		$current_url = get_permalink();
		
		$content .= '<table style="width:100%; padding:0px; margin:0px; border:0px; margin-bottom:10px;">';
		
		$content .= '<tr>
		<th width="33%" style="border:0px; border-bottom: 1px solid #DDDDDD;">Name</th>
		<th width="43%" style="border:0px; border-bottom: 1px solid #DDDDDD;">Address</th>
		<th width="24%" style="border:0px; border-bottom: 1px solid #DDDDDD;">Category</th>
		</tr>';
		
		for($i=0; $i<count($stores); $i++) {
			$id = $stores[$i]['id'];
			$name = $stores[$i]['name'];
			$logo = $stores[$i]['logo'];
			$address = $stores[$i]['address'];
			$url = $stores[$i]['url'];
			$lat = $stores[$i]['lat'];
			$lng = $stores[$i]['lng'];
			$tel = $stores[$i]['tel'];
			$distance = $stores[$i]['distance'];
			$category_name = $stores[$i]['category_name'];
			$marker_icon = $stores[$i]['marker_icon'];
			
			$marker_text = self::getMarkerInfowindowDisplay(array('id'=>$id, 'name'=>$name, 'logo'=>$logo, 'address'=>$address, 'url'=>$url, 'more_details'=>1));
			$content .= '<div style="display:none;" id="infowindow_'.$id.'">'.$marker_text.'</div>';
			$content .= '<div style="display:none;" id="marker_icon_'.$id.'">'.$marker_icon.'</div>';
			
			$content .= '<tr class="displayStoreMap" id="'.$id.'" lat="'.$lat.'" lng="'.$lng.'"
			style="border:0px; cursor:pointer;" onMouseOver="this.style.backgroundColor=\'#eee\'"; onMouseOut="this.style.backgroundColor=\'#fff\'">';
			
				$content .= '<td width="33%" style="padding-right:15px; vertical-align:top; border:0px;">';
					
					$content .= '<a href="'.$current_url.'?store_id='.$id.'">'.$name.'</a>';
					
				$content .= '</td>';
				
				$content .= '<td style="padding-right:15px; vertical-align:top; width:43%; border:0px;">'.$address.'</td>';
				
				$content .= '<td style="padding-right:15px; vertical-align:top; width:24%; border:0px;">'.$category_name.'</td>';
				
			$content .= '</tr>';
		}
		
		$content .= '<tr style="border:0px; margin:0px; padding:0px;">
		<td colspan=3 style="border:0px; border-bottom: 1px solid #DDDDDD; margin:0px; padding:0px;"></td>
		</tr>';
		
		$content .= '</table>';
		
		return $content;
	}
	*/
	
	function displayMarkersContent($locations) {
		
		$sd = new Store_locator_wpress_display();
		
		for($i=0; $i<count($locations);$i++) {
			$id = $locations[$i]['id'];
			$name = $locations[$i]['name'];
			$logo = $locations[$i]['logo'];
			$address = $locations[$i]['address'];
			$url = $locations[$i]['url'];
			
			$markers[$i] .= $this->getMarkerInfowindowDisplay(array('id'=>$id, 'name'=>$name, 'logo'=>$logo, 'address'=>$address, 'url'=>$url, 'more_details'=>1));
		}
		return $markers;
	}
	
	function getMarkerInfowindowDisplay($criteria=array()) {
		$id = $criteria['id'];
		$name = $criteria['name'];
		$address = $criteria['address'];
		$url = $criteria['url'];
		$logo = $criteria['logo'];
		$more_details = $criteria['more_details'];
		
		$d .= '<div class="marker_infowindow_box">';
			
			if($logo!='') {
				if($url!='') $d .= '<a href="'.$url.'" target="_blank">';
				$d .= '<img src="'.$logo.'" align="left" style="padding-right:10px;" border=0>';
				if($url!='') $d .= '</a>';
			}
			
			//if($url!='') $d .= '<a href="'.$url.'" target="_blank">';
			$d .= '<div class="marker_infowindow_title_box">'.$name.'</div>';
			//if($url!='') $d .= '</a>';
			
			$d .= '<div class="marker_infowindow_address_box">'.$address.'</div>';
			
			if($more_details==1 || $GLOBALS['ygp_store_locator_wpress']['streetview']==1) {
				$detail_page = get_permalink();
				$d .= '<div class="marker_infowindow_details_box">';
					if($more_details==1) $d .= '<a href="'.$detail_page.'?store_id='.$id.'">'.$GLOBALS['store_locator_lang']['more_details'].'</a>';
					
					if($GLOBALS['ygp_store_locator_wpress']['streetview']==1) {
						if($more_details==1) $d .= ' - ';
						$d .= '<a href="#" id="displayStreetView">'.$GLOBALS['store_locator_lang']['streetview'].'</a>';
					}
					
				$d .= '</div>';
			}
			
			if($GLOBALS['ygp_store_locator_wpress']['direction_links']==1) {
				$d .= '<div class="marker_infowindow_directions_box">';
				$address = str_replace('<br />', ' ', $address);
				$d .= $GLOBALS['store_locator_lang']['get_directions'].': <a href="http://maps.google.com/maps?f=d&z=13&daddr='.urlencode($address).'" target="_blank">'.$GLOBALS['store_locator_lang']['to_here'].'</a> - <a href="http://maps.google.com/maps?f=d&z=13&saddr='.urlencode($address).'" target="_blank">'.$GLOBALS['store_locator_lang']['from_here'].'</a>';
				$d .= '</div>';
			}
			
		$d .= '</div>';
		
		return $d;
	}
	
	function displayPreviousNextButtons($page_number, $nb_stores, $nb_display) {
		if($page_number>1) {
			$display .= '<a href="#" id="store_locator_previous">'.$GLOBALS['store_locator_lang']['previous'].'</a> ';
			$display .= ' - <b>'.$page_number.'</b>';
			$previous_flag=1;
		}
		if($nb_stores>($nb_display*$page_number)) {
			if($previous_flag==1) $display .= ' - ';
			$display .= '<a href="#" id="store_locator_next">'.$GLOBALS['store_locator_lang']['next'].'</a>';
		}
		return $display;
	}
	
	function displayPaginationBox() {
		$content .= '<div class="pagination_box">';
			$content .= '<span id="stores_locator_title" class="pagination_box_title"></span>';
			$content .= '<div style="float:right;" id="previousNextButtons" class="pagination_box_previous_next"></div>';
		$content .= '</div>';
		
		return $content;
	}
	
	function displayAddressSearchBox($criteria=array()) {
		$category_filter = $criteria['category_filter'];
		$distance_filter = $criteria['distance_filter'];
		
		$distance = $GLOBALS['ygp_store_locator_wpress']['distance_unit'];
		
		$display = '<div>'.$GLOBALS['store_locator_lang']['search_by_address'].'</div>';
		
		$display .= '<form method="GET">';
			$display .= '<input type="text" id="store_wpress_address" name="store_wpress_address" style="width:440px;" value="'.$_GET['address'].'" />';
			$display .= ' <input type="submit" id="store_wpress_search_btn" value="'.$GLOBALS['store_locator_lang']['search'].'" style="padding:2px;"/>';
			
			if($category_filter==1 || $distance_filter==1) {
				
				$display .= '<div style="margin-bottom:20px; margin-top:10px;">';
				
				/*
				if($GLOBALS['store_locator_settings']['category2_flag']==1) {
					$db1 = new Store_locator_wpress_db();
					$categories = $db1->return_categories2();
					
					$nb_stores_by_cat = $db1->return_nb_stores_by_category2();
					
					$display .= $GLOBALS['store_locator_lang']['category2'].': ';
					$display .= '<select id="store_wpress_category2_filter">';
					$display .= '<option value="">'.$GLOBALS['store_locator_lang']['all_categories2'].'</option>';
					for($i=0; $i<count($categories); $i++) {
						
						$nb_stores = $nb_stores_by_cat[$categories[$i]['id']];
						if($nb_stores=='') $nb_stores=0;
						
						$display .= '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].' ('.$nb_stores.')</option>';
					}
					$display .= '</select>&nbsp;&nbsp;&nbsp;';
				}
				*/
				
				if($category_filter==1) {
					$db1 = new Store_locator_wpress_db();
					$categories = $db1->return_categories();
					
					$nb_stores_by_cat = $db1->return_nb_stores_by_category();
					
					$display .= $GLOBALS['store_locator_lang']['category'].': ';
					$display .= '<select id="store_wpress_category_filter">';
					$display .= '<option value="">'.$GLOBALS['store_locator_lang']['all_categories'].'</option>';
					for($i=0; $i<count($categories); $i++) {
						
						$nb_stores = $nb_stores_by_cat[$categories[$i]['id']];
						if($nb_stores=='') $nb_stores=0;
						
						$display .= '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].' ('.$nb_stores.')</option>';
					}
					$display .= '</select>';
				}
				
				if($distance_filter==1) {
					$display .= '&nbsp;&nbsp;&nbsp;'.$GLOBALS['store_locator_lang']['distance'].': ';
					$display .= '<select id="store_wpress_distance_filter">';
					$display .= '<option value=""></option>';
					for($i=0; $i<count($GLOBALS['store_locator_settings']['distance']); $i++) {
						$display .= '<option value="'.$GLOBALS['store_locator_settings']['distance'][$i].'">'.$GLOBALS['store_locator_settings']['distance'][$i].' '.$distance.'</option>';
					}
					$display .= '</select>';
				}
				
				$display .= '</div>';
			}
			
		$display .= '</form>';
		
		return $display;
	}
}

new Store_locator_wpress_display();

?>