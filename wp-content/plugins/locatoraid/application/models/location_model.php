<?php
class Location_model extends MY_Model
{
	const PRIORITY_NO = 0;
	const PRIORITY_FEATURED = 1;
	const PRIORITY_ALWAYS = 2;
	var $types = array();
	var $strict_search = TRUE;

	function __construct()
	{
		parent::__construct();
		$types_file = dirname(__FILE__) . '/../../types.php';
		if( file_exists($types_file) )
		{
			$types = array();
			require( $types_file );
			$this->types = $types;
		}
	}

	function get_countries()
	{
		$this->db->distinct();
		$this->db->select( 'country' );
		$result	= $this->db->get('locations');

		$return	= array();
		foreach( $result->result_array() as $e )
		{
			$e['country'] = trim( $e['country'] );
			if( strlen($e['country']) )
			{
				$return[ ucwords($e['country']) ] = 1;
			}
		}

		if( $return && (count($return) > 1) )
		{
			$return = array_keys( $return );
			sort( $return );
		}
		else
		{
			$return = array();
		}

		return $return;
	}

	function get_types()
	{
		return $this->types;
	}

	function type_title( $type_id )
	{
		$types = $this->get_types();
		$return = isset($types[$type_id]) ? $types[$type_id] : 'Unknown';
		return $return;
	}

	function condition_failed()
	{
		$this->db->where( 'latitude', -1 );
		$this->db->where( 'longitude', -1 );
	}

	function priority_always()
	{
		return self::PRIORITY_ALWAYS;
	}

	function condition_not_yet()
	{
		$this->db->where( '(latitude = 0 OR latitude = "" OR latitude IS NULL)' );
		$this->db->where( '(longitude = 0 OR longitude = "" OR longitude IS NULL)' );
	}

	function get_priority_title( $priority )
	{
		$priorities = $this->get_priorities();
		$return = isset($priorities[$priority]) ? $priorities[$priority] : 'N/A';
		return $return;
	}

	function get_priorities()
	{
		return array(
			self::PRIORITY_NO		=> lang('location_priority_no'),
			self::PRIORITY_FEATURED	=> lang('location_priority_featured'),
			self::PRIORITY_ALWAYS	=> lang('location_priority_always'),
			);
	}

	function get_fields()
	{
		$misc_titles = array();
		for( $ii = 1; $ii <= 5; $ii++ )
		{
			$misc_title = $this->app_conf->get( 'form_misc' . $ii );
			if( ! $misc_title )
				$misc_title = lang('location_misc') . ' ' . $ii;
			$misc_titles[ $ii ] = $misc_title;
		}

		$return = array(
			array(
				'name'	=> 'name',
				'title'	=> lang('location_name'),
				'size'	=> 24,
				'required'	=> TRUE,
				),
			array(
				'name'	=> 'street1',
				'title'	=> lang('location_street1'),
				'size'	=> 24,
				'required'	=> TRUE,
				),
			array(
				'name'	=> 'street2',
				'title'	=> lang('location_street2'),
				'size'	=> 24,
				),

			array(
				'name'	=> 'city',
				'title'	=> lang('location_city'),
				'size'	=> 24,
				),
			array(
				'name'	=> 'state',
				'title'	=> lang('location_state'),
				'size'	=> 12,
				),
			array(
				'name'	=> 'zip',
				'title'	=> lang('location_zip'),
				'size'	=> 12,
				),
			array(
				'name'	=> 'country',
				'title'	=> lang('location_country'),
				'size'	=> 24,
				),

			array(
				'name'	=> 'phone',
				'title'	=> lang('location_phone'),
				'size'	=> 12,
				),
			);

		$website_title = $this->app_conf->get( 'form_website' );
		if( ! $website_title )
			$website_title = '';
		$return[] = array(
			'name'	=> 'website',
			'title'	=> $website_title,
			'size'	=> 32,
			);

		if( Modules::exists('pro') )
		{
			array_unshift(
				$return,
				array(
					'name'		=> 'priority',
					'title'		=> lang('location_priority'),
					'type'		=> 'dropdown',
					'options'	=> $this->get_priorities(),
					)
				);

			for( $ii = 1; $ii <= 5; $ii++ )
			{
				$return[] = array(
					'name'	=> 'misc' . $ii,
					'title'	=> $misc_titles[$ii],
					'size'	=> 24,
					);
			}

			$products_title = $this->app_conf->get( 'form_products' );
			if( ! $products_title )
				$products_title = lang('location_products');

			$return[] = array(
				'name'	=> 'products',
				'title'	=> $products_title,
				'size'	=> 24,
				);
		}

		/* types */
		$types = $this->get_types();
		if( $types )
		{
			array_unshift( $return, array(
				'name'		=> 'loc_type',
				'title'		=> lang('location_type'),
				'type'		=> 'dropdown',
				'options'	=> $types,
				));
		}

		return $return;
	}

	function already_exists( $e )
	{
		$address_fields = array( 'name', 'street1', 'street2', 'city', 'state', 'zip', 'country' );
		$address = array();
		reset( $address_fields );
		foreach( $address_fields as $af )
		{
			$address[] = isset($e[$af]) ? $e[$af] : '';
		}
		$check = join( ', ', $address );
		$check_fields = 'CONCAT_WS( ", ", ' . join( ', ', $address_fields ) . ')';

		$this->db->where($check_fields . '=', $check);
		if ( isset($e['id']) && $e['id'])
		{
			$this->db->where('id <>', $e['id']);
		}
		$return = $this->db->count_all_results('locations');
		return $return;
	}

	function make_address( $e, $for_display = FALSE, $append_search = TRUE )
	{
		if( $for_display )
		{
			$address = array();
			if( $e['street1'] )
				$address[] = $e['street1'];
			if( $e['street2'] )
				$address[] = $e['street2'];

			$address2 = array();
			foreach( array('city', 'state', 'zip') as $af )
			{
				if( $e[$af] )
					$address2[] = $e[$af];
			}
			if( $address2 )
			{
				$address2 = join( ' ', $address2 );
				$address[] = $address2;
			}

			$address3 = array();
			foreach( array('country') as $af )
			{
				if( $e[$af] )
				{
					$address3[] = strtoupper($e[$af]);
				}
				else
				{
					$append_search = TRUE;
				}
			}
			if( $address3 )
			{
				$address3 = join( ' ', $address3 );
				$address[] = $address3;
			}

			$address = join( '<br>', $address );
		}
		else
		{
			$address_fields = array( 'street1', 'street2', 'city', 'state', 'zip', 'country' );
			$address = array();
			reset( $address_fields );
			foreach( $address_fields as $af )
			{
				if( $e[$af] )
					$address[] = $e[$af];
			}

			$CI =& ci_get_instance();
			if( $append_search )
			{
				$append_search = $CI->app_conf->get( 'append_search' );
				if( $append_search )
				{
					$append_search = strtolower( $append_search );
					$test_address = join( ', ', $address );
					$test_address = strtolower( $test_address );
				// check if it already ends with append
					if( substr($test_address, 0, strlen($append_search)) != $append_search )
					{
						$address[] = $append_search;
					}
				}
			}
			$address = join( ', ', $address );
		}
		return $address;
	}

	function count_all()
	{
		$return = $this->db->count_all_results('locations');
		return $return;
	}

	function get_all_by_search( $search2 = array() )
	{
		$limit = 0;
		$what = '*';
		$measure = $this->app_conf->get( 'measurement' );
		$measure_text = lang( 'conf_measurement_' . $measure );

		$this->db->where(
			"(
			longitude !=0 AND 
			latitude !=0 AND 
			longitude IS NOT NULL AND
			latitude IS NOT NULL AND
			longitude !=-1 AND 
			latitude !=-1
			)
			"
			);

		$this->db->select($what);
		$this->db->order_by('priority', 'DESC');
		$this->db->order_by('name', 'ASC');
		$result	= $this->db->get('locations');

		$skip_this = FALSE;
		if( $search2 )
		{
			$search_in_parts = array( 'name', 'misc1', 'misc2', 'misc3', 'misc4', 'misc5', 'products' );
		}

		$return	= array();
		foreach( $result->result_array() as $e )
		{
			$skip_this = FALSE;
			if( $search2 && ($e['priority'] < self::PRIORITY_ALWAYS) )
			{
				$skip_this = TRUE;

				$search_in = array();
				reset( $search_in_parts );
				foreach( $search_in_parts as $p )
				{
					if( strlen($e[$p]) )
						$search_in[] = $e[$p];
				}
				$search_in = join( ' ', $search_in );
				$search_in = strtolower( $search_in );

				reset( $search2 );
				foreach( $search2 as $s2 )
				{
					$s2 = trim( $s2 );
					if( ! strlen($s2) )
						continue;
					$s2 = strtolower( $s2 );
					if( strpos($search_in, $s2) !== FALSE )
					{
						$skip_this = FALSE;
						break;
					}
					if( ! $skip_this )
						break;
				}
			}

			if( $skip_this )
				continue;

			$e['distance_num'] = 0;
			$e['distance'] = '';
			$return[] = $e;
		}
		return $return;
	}

	function get_nearest( $my_lat, $my_long, $within, $search2 = array(), $type = 0 )
	{
		$limit = 0;
		$what = '*';
		$measure = $this->app_conf->get( 'measurement' );
		$measure_text = lang( 'conf_measurement_' . $measure );
		/* miles */
		if( $measure == 'miles' ){
			$nau2measure = 1.1508;
			$per_grad = 69;
			}
		/* km */
		else {
			$nau2measure = 1.852; 
			$per_grad = 111.04;
			}

		$what = "
			*,
			DEGREES(
			ACOS(
				SIN(RADIANS(latitude)) * SIN(RADIANS($my_lat))
			+	COS(RADIANS(latitude)) * COS(RADIANS($my_lat))
			*	COS(RADIANS(longitude - ($my_long)))
			) * 60 * $nau2measure
			) AS distance
			";

	// limit within
		$where1 = '1';
		if( $within > 0 )
		{
			$lat1 = $my_lat - ($within/$per_grad);
			$lat2 = $my_lat + ($within/$per_grad);
			$long1 = $my_long - $within / abs( cos(deg2rad($my_lat)) * $per_grad );
			$long2 = $my_long + $within / abs( cos(deg2rad($my_lat)) * $per_grad );

			$this->db->where( 'latitude > ', $lat1 );
			$this->db->where( 'latitude < ', $lat2 );	
			$this->db->where( 'longitude > ', $long1 );
			$this->db->where( 'longitude < ', $long2 );

			$where1 = join( ' ', $this->db->ar_where ); // hack
			$this->db->ar_where = array();
		}

		$this->db->or_where( 'priority', self::PRIORITY_ALWAYS );
		$where2 = join( ' ', $this->db->ar_where ); // hack
		$this->db->ar_where = array();

		$this->db->where(
			"(
			longitude !=0 AND 
			latitude !=0 AND 
			longitude IS NOT NULL AND
			latitude IS NOT NULL AND
			longitude !=-1 AND 
			latitude !=-1
			)
			AND
			(
				($where1) OR ($where2)
			)
			"
			);

	/* types */
		$types = $this->get_types();
		if( $types )
		{
			if( $type )
				$this->db->where( 'loc_type >=', $type );
			else
				$this->db->where( 'loc_type', $type );
		}

		$this->db->select($what);
		$this->db->order_by('priority', 'DESC');
		$this->db->order_by('distance', 'ASC');
		$result	= $this->db->get('locations');

		$skip_this = FALSE;
		if( $search2 )
		{
			$search_in_parts = array( 'name', 'misc1', 'misc2', 'misc3', 'misc4', 'misc5', 'products' );
		}

		$return	= array();
		foreach( $result->result_array() as $e )
		{
			$skip_this = FALSE;
			if( $search2 && ($e['priority'] < self::PRIORITY_ALWAYS) )
			{
				$skip_this = TRUE;

				$search_in = array();
				reset( $search_in_parts );
				foreach( $search_in_parts as $p )
				{
					if( strlen($e[$p]) )
						$search_in[] = $e[$p];
				}
				$search_in = join( ' ', $search_in );
				$search_in = strtolower( $search_in );

				if( $this->strict_search )
				{
					$s2 = join( ' ', $search2 );
					if( ! strlen($s2) )
						continue;
					$s2 = strtolower( $s2 );
					if( strpos($search_in, $s2) !== FALSE )
					{
						$skip_this = FALSE;
					}
				}
				else
				{
					reset( $search2 );
					foreach( $search2 as $s2 )
					{
						$s2 = trim( $s2 );
						if( ! strlen($s2) )
							continue;
						$s2 = strtolower( $s2 );
						if( strpos($search_in, $s2) !== FALSE )
						{
							$skip_this = FALSE;
							break;
						}
						if( ! $skip_this )
							break;
					}
				}
			}

			if( $skip_this )
			{
				continue;
			}

			if( $e['distance'] > $within )
			{
				continue;
			}

			$e['distance_num'] = $e['distance'];
			$e['distance'] = round( $e['distance'], 2 ) . ' ' . $measure_text;
			$return[] = $e;
		}

		return $return;
	}

	function get_all( $fields = array() )
	{
		$what = $fields ? join(',', $fields) : '*';
		$this->db->select($what);
		$this->db->order_by('name', 'ASC');
		$result	= $this->db->get('locations');

		$return	= array();
		foreach($result->result_array() as $e)
		{
//			$e['categories'] = $this->get_categories( $e['id'] );
			$return[] = $e;
		}
		return $return;
	}

	function get($id)
	{
		return $this->db->get_where('locations', array('id'=>$id))->row_array();
	}

	function get_categories($id)
	{
		$this->db->select('categories.*');
		$this->db->from('categories'); 
		$this->db->join('locations_categories', 'locations_categories.category_id = categories.id');
		$this->db->where(array('locations_categories.location_id' => $id));

		$result = $this->db->get();

		$return	= array();
		foreach($result->result_array() as $i)
		{
			$return[] = $i;
		}
		return $return;
	}

	function save($object, $rewrite_coord = FALSE )
	{
		if( $this->already_exists($object) )
		{
			return FALSE;
		}

		if( isset($object['categories']) ){
			$categories = $object['categories'];
			unset( $object['categories'] );
		}
		else
		{
			$categories = array();
		}
		reset( $categories );

		if ( isset($object['id']) && $object['id'])
		{
		// reset coordinates if address change
			if( ! $rewrite_coord )
			{
				$old_object = $this->get( $object['id'] );
				$old_address = $this->make_address( $old_object );
				$new_address = $this->make_address( $object );
				if( $old_address != $new_address )
				{
					$object['latitude'] = 0;
					$object['longitude'] = 0;
				}
			}

			$this->db->where('id', $object['id']);
			$this->db->update('locations', $object);
			$myId = $object['id'];
		}
		else
		{
			$this->db->insert('locations', $object);
			$myId = $this->db->insert_id();

			if( $categories )
			{
				reset( $categories );
				foreach( $categories as $cid ){
					$this->db->insert('locations_categories', array('item_id' => $myId, 'category_id' => $cid ));
					}
			}
		}

	/* update products if any */
		if( isset($object['products']) )
		{
			$products_changed = FALSE;
			$current_products = $this->app_conf->get('products');
			$current_products = strlen($current_products) ? explode('||', $current_products) : array();

			$products = $object['products'];
			$products = explode( ',', $products );
			$products = array_map( 'trim', $products );
			reset( $products );
			foreach( $products as $pn )
			{
				if( strlen($pn) )
				{
					if( ! in_array($pn, $current_products) )
					{
						$current_products[] = $pn;
						$products_changed = TRUE;
					}
				}
			}

			if( $products_changed )
			{
				$value = join( '||', $current_products );
				$this->app_conf->set( 'products', $value );
			}
		}

	return $myId;
	}

	function delete($id)
	{
		$object = $this->get( $id );

		$this->db->where('id', $id);
		$this->db->delete('locations');

	/* update products if any */
		if( isset($object['products']) )
		{
			$products_changed = FALSE;
			$current_products = $this->app_conf->get('products');
			$current_products = strlen($current_products) ? explode('||', $current_products) : array();
			reset( $current_products );
			$current_products2 = array();
			foreach( $current_products as $cp )
			{
				$current_products2[ $cp ] = 1;
			}

			$products = $object['products'];
			$products = explode( ',', $products );
			$products = array_map( 'trim', $products );
			reset( $products );
			foreach( $products as $pn )
			{
				if( strlen($pn) )
				{
					// check if we still have something
					$this->db->like( 'products', $pn );
					$remaining_count = $this->count_all();
					if( ! $remaining_count )
					{
						if( isset($current_products2[$pn]) )
						{
							unset( $current_products2[$pn] );
							$products_changed = TRUE;
						}
					}
				}
			}

			if( $products_changed )
			{
				$value = join( '||', array_keys($current_products2) );
				$this->app_conf->set( 'products', $value );
			}
		}

		//delete references to this item
/*
		$this->db->where('location_id', $id);
		$this->db->delete('locations_categories');
*/
		if( Modules::exists('locazip') )
		{
			$this->db->where('location_id', $id);
			$this->db->delete('companies_locations');
		}
	}

	function get_companies_by( $by, $value )
	{
		$return	= array();

		$lids = array();
		$this->db->select( 'id' );
		$this->db->from( 'locations' ); 
		$this->db->where( 'locations.' . $by, $value );
		$result = $this->db->get();
		foreach($result->result_array() as $i)
		{
			$lids[] = $i['id'];
		}

		if( $lids )
		{
			$this->db->select( '*' );
			$this->db->from( 'companies' ); 
			$this->db->join('companies_locations', 'companies_locations.company_id = companies.id');
			$this->db->where_in( 'companies_locations.location_id', $lids ); 
			$result = $this->db->get();
			foreach($result->result_array() as $i)
			{
				$return[ $i['id'] ] = $i;
			}
		}
		return $return;
	}

	function delete_all()
	{
		$this->db->truncate( 'locations' );

	//delete references to this item
/*
		$this->db->truncate( 'locations_categories' );
*/
	}
	
}