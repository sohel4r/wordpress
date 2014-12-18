<?php

class Store_locator_wpress_admin {
	
	function Store_locator_wpress_admin() {
		$a1 = new Store_locator_wpress_activation();
		if($a1->verify_activation()) {
			add_action( 'admin_menu', array(__CLASS__, 'config_page_init') );
			if(is_admin()) {
				wp_enqueue_script('gmap_api', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'));
				wp_enqueue_script( 'store_wpress_js', plugin_dir_url( __FILE__ ).'include/js/script_admin.js');			
			}
		}
	}
	
	function config_page_init() {
		if (function_exists('add_submenu_page') && is_main_site()) {
			add_menu_page( 'Stores list', 'Stores list', 'manage_options', 'store-locator-wpress', array(__CLASS__, 'store_locator_list'), '', 37 );
			add_submenu_page('store-locator-wpress', 'Add a Store', 'Add a Store', 'manage_options', 'store-locator-wpress-add', array(__CLASS__, 'store_locator_add'));
			add_submenu_page('store-locator-wpress', 'Categories', 'Categories', 'manage_options', 'store-locator-wpress-categories', array(__CLASS__, 'store_locator_categories'));
			add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-update-location', array(__CLASS__, 'store_locator_update_location'));
			add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-edit', array(__CLASS__, 'store_locator_edit'));
			add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-delete', array(__CLASS__, 'store_locator_delete_location'));
			add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-category-edit', array(__CLASS__, 'display_category_edit'));
			add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-category-delete', array(__CLASS__, 'display_category_delete'));
			/*
			if($GLOBALS['store_locator_settings']['category2_flag']==1) {
				add_submenu_page('store-locator-wpress', 'Practices', 'Practices', 'manage_options', 'store-locator-wpress-categories2', array(__CLASS__, 'store_locator_categories2'));
				add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-category2-edit', array(__CLASS__, 'display_category2_edit'));
				add_submenu_page('', '', '', 'manage_options', 'store-locator-wpress-category2-delete', array(__CLASS__, 'display_category2_delete'));
			}
			*/
		}
	}
	
	function store_locator_categories() {
		?>
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		<?php
		
		$sl1 = new Store_locator_wpress_db();
		
		if(isset($_POST['add'])) {
			$sl1->add_category(array('name'=>$_POST['name'], 'marker_icon'=>$_POST['marker_icon']));
		}
		
		$categories = $sl1->return_categories();
		$storesByCat = $sl1->return_nb_stores_by_category();
		
		echo '<h2>Stores Categories</h2>';
		echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
		
		for($i=0; $i<count($categories); $i++) {
			if($storesByCat[$categories[$i]['id']]>0) $nb=$storesByCat[$categories[$i]['id']];
			else $nb=0;
			echo '<table width="100%" style="padding-bottom:10px; margin-bottom:10px; border-bottom: 1px solid #e7e7e7;"><tr>';
			echo '<td>';
			echo '<b>'.$categories[$i]['name'].'</b> (Stores: '.$nb.' - Category id: '.$categories[$i]['id'].')';
			echo '</td>';
			echo '<td align="right">';
			echo '<a href="./admin.php?page=store-locator-wpress-category-edit&id='.$categories[$i]['id'].'">Edit</a> - ';
			echo '<a href="./admin.php?page=store-locator-wpress-category-delete&id='.$categories[$i]['id'].'">Delete</a>';
			echo '</td>';
			echo '</tr></table>';
		}
		
		if(count($categories)==0) echo '<br>You don\'t have any category yet.';
		
		echo '<form method="post">';
			echo '<h2>Add a new category</h2>';
			echo '<p>';
			echo 'Name: <input class="widefat" name="name" style="width:360px; font-family: \'Courier New\', Courier, mono; font-size: 1.4em;"></p>';
			echo 'Marker icon URL: <input class="widefat" name="marker_icon" style="width:360px; font-family: \'Courier New\', Courier, mono; font-size: 1.4em;"></p>';
			echo '<p><input class="button-primary" type="submit" name="add" value="Add"></p>';
		echo '</form>';
		
		?>
		</div></div>
		<?php
	}
	
	function display_category_edit() {
		
		?>
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		<?php
		
		$sl1 = new Store_locator_wpress_db();
		
		if(isset($_POST['edit'])) {
			$sl1->update_category(array('name'=>$_POST['name'], 'marker_icon'=>$_POST['marker_icon'], 'id'=>$_POST['id']));
			
			echo '<script>';
			echo 'window.location = "./admin.php?page=store-locator-wpress-categories"';
			echo '</script>';
		}
		
		else {
			$categories = $sl1->return_categories(array('id'=>$_GET['id']));
			
			echo '<h2>Edit a category</h2>';
			echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
			
			echo '<form method="post">';
				echo '<input type="hidden" name="id" value="'.$categories[0]['id'].'">';
				echo '<p>';
				echo 'Name: <input class="widefat" name="name" value="'.$categories[0]['name'].'" style="width:360px; font-family: \'Courier New\', Courier, mono; font-size: 1.4em;"></p>';
				echo 'Marker icon URL: <input class="widefat" name="marker_icon" value="'.$categories[0]['marker_icon'].'" style="width:360px; font-family: \'Courier New\', Courier, mono; font-size: 1.4em;"></p>';
				echo '<p><input class="button-primary" type="submit" name="edit" value="Edit"></p>';
			echo '</form>';
		}
		
		?>
		</div></div>
		<?php
	}
	
	function display_category_delete() {
		?>
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		<?php
		echo '<h2>Delete a category</h2>';
		echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
		
		$sl1 = new Store_locator_wpress_db();
		
		if($_GET['confirm']==1) {
			$s1 = new Store_locator_wpress_db();
			$display = $s1->delete_category($_GET['id']);
			
			echo '<script>';
			echo 'window.location = "./admin.php?page=store-locator-wpress-categories"';
			echo '</script>';
			
			//echo '<p>'.$display.'<p>';
			//echo '<a href="./admin.php?page=store-locator-wpress-categories">Categories list</a>';
		}
		
		else {
			$categories = $sl1->return_categories(array('id'=>$_GET['id']));
			
			echo '<p><b>Name:</b> '.$categories[0]['name'].'</p>';
			echo '<p>Are you sure you want to delete this category?</p>';
			echo '<a href="./admin.php?page=store-locator-wpress-category-delete&id='.$_GET['id'].'&confirm=1">Yes, delete this category</a> - <a href="./admin.php?page=store-locator-wpress-categories">Cancel</a>';
		}
		
		?>
		</div></div>
		<?php
	}
	
	function store_locator_list() {
		?>
		
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		
		<?php
		$sl1 = new Store_locator_wpress_db();
		$stores = $sl1->return_stores();
		
		echo '<h2>Stores list <font size="-1">';
		if(count($stores)>0) echo '(<a href="./admin.php?page=store-locator-wpress-add">Add a store</a>)</font>';
		echo '</h2>';
		echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
		
		//get categories list (id + name)
		$categories = $sl1->return_categories();
		for($i=0; $i<count($categories); $i++) {
			$categories_list[$categories[$i]['id']] = $categories[$i]['name'];
		}
		
		for($i=0; $i<count($stores); $i++) {
			$latLng = 'Lat: '.$stores[$i]['lat'].', Lng: '.$stores[$i]['lng'];
			
			echo '<table width="100%" style="padding-bottom:10px; margin-bottom:10px; border-bottom: 1px solid #e7e7e7;"><tr>';
			echo '<td>';
			echo '<h2>'.$stores[$i]['name'].' ';
			if($stores[$i]['category_id']>0) echo '<small><font size="-1">(<span style="color:#921414">'.$categories_list[$stores[$i]['category_id']].'</span>)</font></small>';
			echo '</h2>';
			echo ''.$stores[$i]['address'];
			echo ' <small><font color="blue">('.$latLng.')</font></small>';
			echo '</td>';
			echo '<td align="right">';
			echo '<a href="./admin.php?page=store-locator-wpress-edit&id='.$stores[$i]['id'].'">Edit</a> - ';
			echo '<a href="./admin.php?page=store-locator-wpress-delete&id='.$stores[$i]['id'].'">Delete</a>';
			echo '</td>';
			echo '</tr></table>';
		}
		
		if(count($stores)==0) echo '<br>You don\'t have any store yet: <a href="./admin.php?page=store-locator-wpress-add">Add a new store</a>';
		
		?>
		</div></div>
		<?php
	}
	
	function store_locator_delete_location() {
		$id = $_GET['id'];
		
		?>
		
		<div class="wrap">
		<div class="metabox-holder">
		
			<h2>Delete a store</h2>
			<hr style="background:#ddd;color:#ddd;height:1px;border:none;">
			
			<?php
			if(isset($_GET['confirm'])) {
				$s1 = new Store_locator_wpress_db();
				$display = $s1->delete_store($id);
				echo '<p>'.$display.'<p>';
				echo '<a href="./admin.php?page=store-locator-wpress">Store list</a>';
			}
			else {
				$s1 = new Store_locator_wpress_db();
				$store = $s1->return_stores(array('id'=>$id));
				echo '<p><b>Name:</b> '.$store[0]['name'].'</p>';
				echo '<p>Are you sure you want to delete this store?</p>';
				echo '<a href="./admin.php?page=store-locator-wpress-delete&id='.$id.'&confirm=1">Yes, delete this store</a> - <a href="./admin.php?page=store-locator-wpress">Cancel</a>';
			}
			
			?>
			
		</div></div>
		<?php
	}
	
	function store_locator_update_location() {
		$id = $_GET['id'];
		?>
		
		<div class="wrap">
		<div class="metabox-holder">
		
			<h2>Edit an address</h2>
			<hr style="background:#ddd;color:#ddd;height:1px;border:none;">
			
			<?php
			$sl1 = new Store_locator_wpress_db();
			$store = $sl1->return_stores(array('id'=>$_GET['id']));
			
			echo '<p><b>Store name</b>: '.$store[0]['name'].'</p>';
			
			if(isset($_POST['add'])) {
				
				$lat = $geocode['lat'];
				$lng = $geocode['lng'];
								
				$map_url = 'http://maps.google.com/maps/api/staticmap?center='.$lat.','.$lng.'&zoom=15&size=400x250&markers=color:red|'.$lat.','.$lng.'&sensor=false';
				
				echo '<table><tr><td><img src="'.$map_url.'" style="padding-right:20px;"></td>';
				echo '<td valign="top"><b>Address:</b><br>'.$_POST['address'].'</td></tr></table>';
				
				if($lng!='' && $lng!='') {
					echo '<br><form method="post">';
					echo '<input type="hidden" name="lat" value="'.$lat.'">';
					echo '<input type="hidden" name="lng" value="'.$lng.'">';
					echo '<input type="hidden" name="address" value="'.$_POST['address'].'">';
					echo '<p class="submit" style="padding-bottom:0px; padding-top:0px;">';
					echo '<input class="button-primary" type="submit" name="save" value="Save this location">';
					echo '</p>';
					echo '</form>';
				}
				else {
					echo '<br>We couldn\'t geocode this address. Please make sure a zip code and a country has been specified, and that no other information than an address related data has been added (no P.O box etc).<br>';
					echo '<a href="javascript:history.go(-1)">Try again</a>';
				}
				
			}
			else {
				echo '<form method="post">';
					echo '<p><label>Address <small>(Full address, including the zip code and country)</small></label></p>';
					echo '<p><input class="widefat" type="text"
					name="address" value="'.$store[0]['address'].'"></p>';
					
					echo '<p class="submit" style="padding-bottom:0px; padding-top:0px;">';
					echo '<input class="button-primary" type="submit" name="add" value="Geocode and preview this address">';
					echo '</p>';
				echo '</form>';
			}
			
			?>
			
		</div></div>
		
		<?php
	}
	
	function get_wp_posts() {
		$posts = get_posts(array('numberposts'=>'500'));
		for($i=0; $i<count($posts);$i++) {
			$posts2[$i]['id'] = $posts[$i]->ID;
			$posts2[$i]['title'] = $posts[$i]->post_title;
			$posts2[$i]['post_status'] = $posts[$i]->post_status;
		}
		return $posts2;
	}
	
	function store_locator_edit() {
		?>
		
		<div class="wrap">
		<div class="metabox-holder">
		
			<h2>Edit a store</h2>
			<hr style="background:#ddd;color:#ddd;height:1px;border:none;">
			
			<?php
			$sl1 = new Store_locator_wpress_db();
			$store = $sl1->return_stores(array('id'=>$_GET['id']));
			
			if(isset($_POST['update'])) {
				$criteria['id'] = $_GET['id'];
				$criteria['post_id'] = $_POST['related_post'];
				$criteria['category_id'] = $_POST['category_id'];
				$criteria['category2_id'] = $_POST['category2_id'];
				$criteria['address'] = $_POST['address'];
				$criteria['lat'] = $_POST['lat'];
				$criteria['lng'] = $_POST['lng'];
				$criteria['name'] = $_POST['name'];
				$criteria['logo'] = $_POST['logo'];
				$criteria['url'] = $_POST['url'];
				$criteria['description'] = $_POST['description'];
				$criteria['tel'] = $_POST['tel'];
				$criteria['email'] = $_POST['email'];
				$sl1->update_store($criteria);
				
				//echo '<p>You store has been updated.</p>';
				//echo '<a href="./admin.php?page=store-locator-wpress">Back to the list</a>';
				echo '<script>window.location = "./admin.php?page=store-locator-wpress";</script>';
			}
			
			else {
				$map_url = 'http://maps.google.com/maps/api/staticmap?center='.$store[0]['lat'].','.$store[0]['lng'].'&zoom=15&size=300x200&markers=color:red|'.$store[0]['lat'].','.$store[0]['lng'].'&sensor=false';
				$map = '<img src="'.$map_url.'">';
				
				echo '<form id="add_store_form" method="post" style="display:none;">';
					echo '<p><label>Address <small>(Full address, including the zip code and country)</small></label></p>';
					echo '<p><input class="widefat" type="text"
					id="address2geocode" name="address2geocode"></p>';
					echo '<p class="submit" style="padding-bottom:0px; padding-top:0px;">';
					echo '<input id="geocode_address_btn" class="button-primary" type="submit" name="add" value="Geocode and preview this address">';
					echo '</p>';
				echo '</form>';
				
				echo '<form id="add_store_form2" method="post">';
					
					echo '<input type="hidden" id="lat" name="lat" value="'.$store[0]['lat'].'">';
					echo '<input type="hidden" id="lng" name="lng" value="'.$store[0]['lng'].'">';
					echo '<input type="hidden" id="address" name="address" value="'.$store[0]['address'].'">';
					
					echo '<table><tr><td id="map_display" style="padding-right:20px;">'.$map.'</td>';
					echo '<td valign="top"><b>Address:</b><br><span id="address_display">'.$store[0]['address'].'</span>
					<br><a href="#" id="edit_geocode_address">Edit address</a>
					</td></tr></table>';
					
					echo '<p><label>Store name</label></p>';
					echo '<p><input class="widefat" type="text" name="name" value="'.$store[0]['name'].'"></p>';
					
					echo '<p><label>Related post <small>(link this store to an existing post)</small></label></p>';
					echo '<p><select class="widefat" name="related_post">';
					echo '<option value=""></option>';
					$posts = self::get_wp_posts();
					for($i=0; $i<count($posts);$i++) {
						if($store[0]['post_id']==$posts[$i]['id']) echo '<option value="'.$posts[$i]['id'].'" selected>'.$posts[$i]['title'].'</option>';
						else echo '<option value="'.$posts[$i]['id'].'">'.$posts[$i]['title'].'</option>';
					}
					echo '</select></p>';
					
					echo '<p><label>Category</label></p>';
					echo '<p><select class="widefat" name="category_id">';
					echo '<option value=""></option>';
					$sl1 = new Store_locator_wpress_db();
					$categories = $sl1->return_categories();
					for($i=0; $i<count($categories);$i++) {
						if($store[0]['category_id']==$categories[$i]['id']) echo '<option value="'.$categories[$i]['id'].'" selected>'.$categories[$i]['name'].'</option>';
						else echo '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].'</option>';
					}
					echo '</select></p>';
					
					if($GLOBALS['store_locator_settings']['category2_flag']==1) {
						echo '<p><label>Practice</label></p>';
						echo '<p><select class="widefat" name="category2_id">';
						echo '<option value=""></option>';
						$sl1 = new Store_locator_wpress_db();
						$categories = $sl1->return_categories2();
						for($i=0; $i<count($categories);$i++) {
							if($store[0]['category2_id']==$categories[$i]['id']) echo '<option value="'.$categories[$i]['id'].'" selected>'.$categories[$i]['name'].'</option>';
							else echo '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].'</option>';
						}
						echo '</select></p>';
					}
	
					echo '<p><label>Logo URL <small>(link to the store image)</small></label></p>';
					echo '<p><input class="widefat" type="text" name="logo" value="'.$store[0]['logo'].'"></p>';
					
					echo '<p><label>URL <small>(should start with http://)</small></label></p>';
					echo '<p><input class="widefat" type="text" name="url" value="'.$store[0]['url'].'"></p>';
	
					echo '<p><label>Description</label></p>';
					echo '<p><textarea class="widefat" type="text" name="description">'.$store[0]['description'].'</textarea>';
					
					echo '<p><label>Telephone</label></p>';
					echo '<p><input class="widefat" type="text" name="tel" value="'.$store[0]['tel'].'"></p>';
					
					echo '<p><label>Email</label></p>';
					echo '<p><input class="widefat" type="text" name="email" value="'.$store[0]['email'].'"></p>';
					
					echo '<p class="submit" style="padding-bottom:0px; padding-top:0px;">';
					echo '<input class="button-primary" type="submit" name="update" value="Update my store information">';
					echo '</p>';
				echo '</form>';
			}
			
			?>
			
		</div></div>
		
		<?php
	}
	
	function store_locator_add() {
		?>
		
		<div class="wrap">
		<div class="metabox-holder">
		
			<h2>Add a store</h2>
			<hr style="background:#ddd;color:#ddd;height:1px;border:none;">
			
			<?php
			
			if(isset($_POST['update'])) {
				
				$user_id = get_current_user_id();
				
				$criteria['user_id'] = $user_id;
				$criteria['post_id'] = $_POST['related_post'];
				$criteria['category_id'] = $_POST['category_id'];
				$criteria['category2_id'] = $_POST['category2_id'];
				$criteria['address'] = $_POST['address'];
				$criteria['lat'] = $_POST['lat'];
				$criteria['lng'] = $_POST['lng'];
				$criteria['name'] = $_POST['name'];
				$criteria['url'] = $_POST['url'];
				$criteria['logo'] = $_POST['logo'];
				$criteria['description'] = $_POST['description'];
				$criteria['tel'] = $_POST['tel'];
				$criteria['email'] = $_POST['email'];
				
				$sl1 = new Store_locator_wpress_db();
				$sl1->add_store($criteria);
				
				//echo '<p>You store has been added.</p>';
				//echo '<a href="./admin.php?page=store-locator-wpress">Back to the list</a>';
				echo '<script>window.location = "./admin.php?page=store-locator-wpress";</script>';
			}
			
			else {
				echo '<form id="add_store_form" method="post">';
					echo '<p><label>Address <small>(Full address, including the zip code and country)</small></label></p>';
					echo '<p><input class="widefat" type="text"
					id="address2geocode" name="address2geocode"></p>';
					echo '<p class="submit" style="padding-bottom:0px; padding-top:0px;">';
					echo '<input id="geocode_address_btn" class="button-primary" type="submit" name="add" value="Geocode and preview this address">';
					echo '</p>';
				echo '</form>';
				
				echo '<form id="add_store_form2" method="post" style="display:none;">';
					
					echo '<table><tr><td id="map_display" style="padding-right:20px;"></td>';
					echo '<td valign="top"><b>Address:</b><br><span id="address_display"></span>
					<br><a href="#" id="edit_geocode_address">Edit address</a>
					</td></tr></table>';
					
					echo '<input type="hidden" id="lat" name="lat">';
					echo '<input type="hidden" id="lng" name="lng">';
					echo '<input type="hidden" id="address" name="address">';
					
					echo '<p><label>Store name</label></p>';
					echo '<p><input class="widefat" type="text" name="name"></p>';
					
					echo '<p><label>Related post <small>(link this store to an existing post)</small></label></p>';
					echo '<p><select class="widefat" name="related_post">';
					echo '<option value=""></option>';
					$posts = self::get_wp_posts();
					for($i=0; $i<count($posts);$i++) {
						echo '<option value="'.$posts[$i]['id'].'">'.$posts[$i]['title'].'</option>';
					}
					echo '</select></p>';
					
					echo '<p><label>Category</label></p>';
					echo '<p><select class="widefat" name="category_id">';
					echo '<option value=""></option>';
					$sl1 = new Store_locator_wpress_db();
					$categories = $sl1->return_categories();
					for($i=0; $i<count($categories);$i++) {
						echo '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].'</option>';
					}
					echo '</select></p>';
					
					if($GLOBALS['store_locator_settings']['category2_flag']==1) {
						echo '<p><label>Practice</label></p>';
						echo '<p><select class="widefat" name="category2_id">';
						echo '<option value=""></option>';
						$sl1 = new Store_locator_wpress_db();
						$categories = $sl1->return_categories2();
						for($i=0; $i<count($categories);$i++) {
							echo '<option value="'.$categories[$i]['id'].'">'.$categories[$i]['name'].'</option>';
						}
						echo '</select></p>';
					}

					echo '<p><label>Logo URL <small>(link to the store image)</small></label></p>';
					echo '<p><input class="widefat" type="text" name="logo"></p>';
					
					echo '<p><label>URL <small>(should start with http://)</small></label></p>';
					echo '<p><input class="widefat" type="text" name="url"></p>';

					echo '<p><label>Description</label></p>';
					echo '<p><textarea class="widefat" type="text" name="description"></textarea>';
					
					echo '<p><label>Telephone</label></p>';
					echo '<p><input class="widefat" name="tel"></p>';
					
					echo '<p><label>Email</label></p>';
					echo '<p><input class="widefat" name="email"></p>';
					
					echo '<p class="submit" style="padding-bottom:0px; padding-top:0px;">';
					echo '<input class="button-primary" type="submit" name="update" value="Save my store information">';
					echo '</p>';
				echo '</form>';
			}
			
			?>
			
		</div></div>
		<?php
	}
	
	/*
	function store_locator_categories2() {
		?>
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		<?php
		
		$sl1 = new Store_locator_wpress_db();
		
		if(isset($_POST['add'])) {
			$sl1->add_category2(array('name'=>$_POST['name']));
		}
		
		$categories = $sl1->return_categories2();
		$storesByCat = $sl1->return_nb_stores_by_category2();
		
		echo '<h2>Practices</h2>';
		echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
		
		for($i=0; $i<count($categories); $i++) {
			if($storesByCat[$categories[$i]['id']]>0) $nb=$storesByCat[$categories[$i]['id']];
			else $nb=0;
			echo '<table width="100%" style="padding-bottom:10px; margin-bottom:10px; border-bottom: 1px solid #e7e7e7;"><tr>';
			echo '<td>';
			echo '<b>'.$categories[$i]['name'].'</b> (Stores: '.$nb.' - Category id: '.$categories[$i]['id'].')';
			echo '</td>';
			echo '<td align="right">';
			echo '<a href="./admin.php?page=store-locator-wpress-category2-edit&id='.$categories[$i]['id'].'">Edit</a> - ';
			echo '<a href="./admin.php?page=store-locator-wpress-category2-delete&id='.$categories[$i]['id'].'">Delete</a>';
			echo '</td>';
			echo '</tr></table>';
		}
		
		if(count($categories)==0) echo '<br>You don\'t have any practice yet.';
		
		echo '<form method="post">';
			echo '<h2>Add a new practice</h2>';
			echo '<p>';
			echo 'Name: <input class="widefat" name="name" style="width:360px; font-family: \'Courier New\', Courier, mono; font-size: 1.4em;"></p>';
			echo '<p><input class="button-primary" type="submit" name="add" value="Add"></p>';
		echo '</form>';
		
		?>
		</div></div>
		<?php
	}
	
	function display_category2_edit() {
		
		?>
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		<?php
		
		$sl1 = new Store_locator_wpress_db();
		
		if(isset($_POST['edit'])) {
			$sl1->update_category2(array('name'=>$_POST['name'], 'id'=>$_POST['id']));
			
			echo '<script>';
			echo 'window.location = "./admin.php?page=store-locator-wpress-categories2"';
			echo '</script>';
		}
		
		else {
			$categories = $sl1->return_categories2(array('id'=>$_GET['id']));
			
			echo '<h2>Edit a category</h2>';
			echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
			
			echo '<form method="post">';
				echo '<input type="hidden" name="id" value="'.$categories[0]['id'].'">';
				echo '<p>';
				echo 'Name: <input class="widefat" name="name" value="'.$categories[0]['name'].'" style="width:360px; font-family: \'Courier New\', Courier, mono; font-size: 1.4em;"></p>';
				echo '<p><input class="button-primary" type="submit" name="edit" value="Edit"></p>';
			echo '</form>';
		}
		
		?>
		</div></div>
		<?php
	}
	
	function display_category2_delete() {
		?>
		<div class="wrap">
		<div class="metabox-holder">
		<br>
		<?php
		echo '<h2>Delete a category</h2>';
		echo '<hr style="background:#ddd;color:#ddd;height:1px;border:none;">';
		
		$sl1 = new Store_locator_wpress_db();
		
		if($_GET['confirm']==1) {
			$s1 = new Store_locator_wpress_db();
			$display = $s1->delete_category2($_GET['id']);
			
			echo '<script>';
			echo 'window.location = "./admin.php?page=store-locator-wpress-categories2"';
			echo '</script>';
		}
		
		else {
			$categories = $sl1->return_categories2(array('id'=>$_GET['id']));
			
			echo '<p><b>Name:</b> '.$categories[0]['name'].'</p>';
			echo '<p>Are you sure you want to delete this category?</p>';
			echo '<a href="./admin.php?page=store-locator-wpress-category2-delete&id='.$_GET['id'].'&confirm=1">Yes, delete this category</a> - <a href="./admin.php?page=store-locator-wpress-categories2">Cancel</a>';
		}
		
		?>
		</div></div>
		<?php
	}
	*/
}

new Store_locator_wpress_admin();

?>