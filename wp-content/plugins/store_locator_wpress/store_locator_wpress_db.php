<?php

class Store_locator_wpress_db {
	
	var $wpdb;
	var $table_name;
	var $table_name_category;
	
	function Store_locator_wpress_db() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table_name = $wpdb->prefix . "store_wpress";
		$this->table_name_category = $wpdb->prefix . "store_wpress_category";
	}
	
	function setup_tables() {
		if(!is_multisite()) {
			self::create_tables();
			self::update_stores_table();
			self::update_categories_table();			
		}
	}
	
	function update_stores_table() {
		$sql = "DESCRIBE $this->table_name";
		$result = $this->wpdb->get_results($sql, 'ARRAY_N');
		
		for($i=0; $i<count($result); $i++) {
			$field[] = $result[$i][0];
		}
		
		if(!in_array('category_id',$field)) {
			$sql = "ALTER TABLE `$this->table_name` ADD `category_id` INT NOT NULL AFTER `post_id`";
			$this->wpdb->query($sql);
		}
		if(!in_array('country',$field)) {
			$sql = "ALTER TABLE `$this->table_name` ADD `country` VARCHAR( 60 ) NULL AFTER `address`";
			$this->wpdb->query($sql);
		}
		if(!in_array('city',$field)) {
			$sql = "ALTER TABLE `$this->table_name` ADD `city` VARCHAR( 60 ) NULL AFTER `address`";
			$this->wpdb->query($sql);
		}
	}
	
	function update_categories_table() {
		$sql = "DESCRIBE $this->table_name_category";
		$result = $this->wpdb->get_results($sql, 'ARRAY_N');
		
		for($i=0; $i<count($result); $i++) {
			$field[] = $result[$i][0];
		}
		
		if(!in_array('marker_icon',$field)) {
			$sql = "ALTER TABLE `$this->table_name_category` ADD `marker_icon` VARCHAR( 255 ) NULL AFTER `name`";
			$this->wpdb->query($sql);
		}
	}
	
	function create_tables() {
		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
		`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`user_id` BIGINT NOT NULL ,
		`post_id` BIGINT NOT NULL ,
		`category_id` INT NOT NULL ,
		`name` VARCHAR( 160 ) NULL ,
		`logo` VARCHAR( 160 ) NULL ,
		`address` VARCHAR( 160 ) NULL ,
		`lat` VARCHAR( 20 ) NULL ,
		`lng` VARCHAR( 20 ) NULL ,
		`url` VARCHAR( 255 ) NULL ,
		`description` TEXT NULL ,
		`tel` VARCHAR( 30 ) NULL ,
		`email` VARCHAR( 60 ) NULL ,
		`created` DATETIME NULL
		) ENGINE = MYISAM;";
		$this->wpdb->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name_category . " (
		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 120 ) NULL,
		`marker_icon` VARCHAR( 255 ) NULL
		) ENGINE = MYISAM ;";
		
		$this->wpdb->query($sql);
	}
	
	function get_locations($criteria) {
		$lat = $criteria['lat'];
		$lng = $criteria['lng'];
		$page_number = $criteria['page_number'];
		$nb_display = $criteria['nb_display'];
		$distance_unit = $criteria['distance_unit'];
		$category_id = $criteria['category_id'];
		//$category2_id = $criteria['category2_id'];
		$radius_id = $criteria['radius_id'];
		
		$start = ($page_number*$nb_display)-$nb_display;
		
		if($distance_unit=='miles') $distance_unit='3959'; //miles
		else $distance_unit='6371'; //km
		
		$sql = "SELECT s.*, c.marker_icon, c.name category_name,
		( $distance_unit * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$lng."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance 
		FROM ".$this->table_name." s
		LEFT JOIN ".$this->table_name_category." c
		ON s.category_id=c.id
		WHERE 1 ";
		
		if($category_id!='') $sql .= " AND category_id='$category_id'";
		//if($category2_id!='') $sql .= " AND category2_id='$category2_id'";
		
		if($radius_id!='') $sql .= " HAVING distance<='".$radius_id."'";
		
		if($lat!=''&&$lng!='') $sql .= " ORDER BY distance";
		else $sql .= " ORDER BY id DESC";
		
		if($nb_display!='') $sql .= " LIMIT $start, $nb_display";
		
		$locations = $this->wpdb->get_results($sql, 'ARRAY_A');
		
		if(is_main_site()) return $locations;
	}
	
	function return_nb_stores($criteria=array()) {
		$category_id = $criteria['category_id'];
		
		$sql = "SELECT count(*) as nb 
		FROM $this->table_name WHERE 1";
		
		if($category_id!='') $sql .= " AND category_id='$category_id'";
		
		$results = $this->wpdb->get_results($sql, 'ARRAY_A');
		return $results[0];
	}
	
	function return_stores($criteria=array()) {
		$id = $criteria['id'];
		$post_id = $criteria['post_id'];
		$category_id = $criteria['category_id'];
		
		$sql = "SELECT s.*, c.marker_icon 
		FROM $this->table_name s
		LEFT JOIN ".$this->table_name_category." c
		ON s.category_id=c.id
		WHERE 1";
		
		if($id>0) $sql .= " AND s.id='$id'";
		if($post_id>0) $sql .= " AND s.post_id='$post_id'";
		if($category_id>0) $sql .= " AND s.category_id='$category_id'";
		
		$sql .= ' ORDER BY s.created DESC';
		
		$results = $this->wpdb->get_results($sql, 'ARRAY_A');
		return $results;
	}
	
	function return_categories($criteria=array()) {
		$id = $criteria['id'];
		$sql = "SELECT * FROM $this->table_name_category WHERE 1";
		if($id>0) $sql .= " AND id='$id'";
		$sql .= ' ORDER BY name';
		
		$results = $this->wpdb->get_results($sql, 'ARRAY_A');
		return $results;
	}
	
	function return_nb_stores_by_category() {
		$sql = 'SELECT c.id, count(*) nb 
		FROM '.$this->table_name.' s, '.$this->table_name_category.' c 
		WHERE s.category_id=c.id GROUP BY s.category_id';
		$results = $this->wpdb->get_results($sql, 'ARRAY_A');
		for($i=0; $i<count($results); $i++) {
			$storesCat[$results[$i]['id']] = $results[$i]['nb'];
		}
		return $storesCat;
	}
	
	function delete_store($id) {
		$sql = "DELETE FROM $this->table_name WHERE id='%d'";
		$this->wpdb->query($this->wpdb->prepare($sql, $id));
		return 'The store has been deleted.';
	}
	
	function delete_category($id) {
		$sql = "SELECT * FROM $this->table_name WHERE category_id='$id'";
		$results = $this->wpdb->get_results($sql, 'ARRAY_A');
		if(count($results)>0) {
			return "You cannot delete this category because it's containing ".count($results)." store(s). Please delete the stores first then try again.";
		}
		else {
			$sql = "DELETE FROM $this->table_name_category WHERE id='%d'";
			$this->wpdb->query($this->wpdb->prepare($sql, $id));
			return 'The category has been deleted.';
		}
	}
	
	function update_store($criteria) {
		$sql = "UPDATE $this->table_name SET 
		post_id='".$criteria['post_id']."', category_id='".$criteria['category_id']."', 
		name='".$criteria['name']."', logo='".$criteria['logo']."', url='".$criteria['url']."', 
		address='".$criteria['address']."', lat='".$criteria['lat']."', lng='".$criteria['lng']."', 
		description='".$criteria['description']."', tel='".$criteria['tel']."', email='".$criteria['email']."'
		WHERE id='".$criteria['id']."'";
		$this->wpdb->query($sql);
	}
	
	function update_category($criteria) {
		$sql = "UPDATE $this->table_name_category SET name='".$criteria['name']."', marker_icon='".$criteria['marker_icon']."' 
		WHERE id='".$criteria['id']."'";
		$this->wpdb->query($sql);
	}
	
	function add_store($criteria) {
		$sql = "INSERT INTO $this->table_name 
		(user_id, post_id, category_id, name, logo, address, lat, lng, url, description, tel, email, created) 
		VALUES ('".$criteria['user_id']."', '".$criteria['post_id']."', '".$criteria['category_id']."', '".$criteria['name']."', '".$criteria['logo']."', '".$criteria['address']."', '".$criteria['lat']."', '".$criteria['lng']."', 
		'".$criteria['url']."', '".$criteria['description']."', '".$criteria['tel']."', '".$criteria['email']."', '".date('Y-m-d H:i:s')."')";
		if(is_main_site()) $this->wpdb->query($sql);
	}
	
	function add_category($criteria=array()) {
		$name = $criteria['name'];
		$marker_icon = $criteria['marker_icon'];
		
		$sql = "INSERT INTO $this->table_name_category (name, marker_icon) VALUES ('".$name."', '".$marker_icon."')";
		$this->wpdb->query($sql);
	}
	
}

?>