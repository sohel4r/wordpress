<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Front extends Front_controller
{
	var $default_search = '';
	var $front_template = '';

	function __construct()
	{
		parent::__construct();
		$this->load->model( 'Location_model', 'model' );
		$this->model->strict_search = TRUE;
	}

	function start( $search, $search2 = '' )
	{
		if( $search != '_all_' )
		{
			if( $search == '_' )
				$search = '';
			$search = urldecode($search);
			$this->default_search = $search;
		}
		return $this->index( $search2 );
	}

	function get( $lat = 0, $long = 0, $log_it = 1 )
	{
		if( $lat == 'init' )
		{
			global $SEC;
			echo $SEC->get_csrf_hash();
			exit;
		}

		$within = $this->input->post('within');

	// within options
		$within_options = array();
		$search_within = $this->app_conf->get('search_within');
		foreach( explode(',', $search_within) as $wo )
		{
			$wo = trim($wo);
			if($wo)
				$within_options[] = $wo;
		}
		sort( $within_options );

		if( (! $within) OR (! in_array($within, $within_options)) )
		{
			$within = $within_options[0];
		}

		$lat = str_replace( '_', '-', $lat );
		$long = str_replace( '_', '-', $long );

		$search = $this->default_search ? $this->default_search : $this->app_conf->get('default_search');
		$search2 = $this->input->post('search2');
		$address = $this->input->post('address');

		$search2 = trim( $search2 );
		if( in_array($search2, array('_', '-')) )
			$search2 = '';
		$str_search2 = '';

		if( $search2 )
		{
			$search2 = urldecode( $search2 );
			$search2 = trim( $search2 );
			$str_search2 = $search2;
			$search2 = ($search2 !== '') ? explode( ' ', $search2 ) : array();
		}

		$entries = array();

		$get_what = '';
		if( $lat == '-all-' )
		{
			$get_what = 'all';
		}
		elseif( $lat && $long )
		{
			$get_what = 'nearest';
		}

		switch( $get_what )
		{
			case 'all':
				$entries = $this->model->get_all_by_search( $search2 );
				break;
			case 'nearest':
				$entries = $this->model->get_nearest( $lat, $long, $within, $search2 );
				break;
		}

	/* if some props are set as hidden */
		$hide_props = array();
		$all_fields = $this->model->get_fields();
		reset( $all_fields );
		foreach( $all_fields as $f )
		{
			$hide_name = 'form_' . $f['name'] . '_hide';
			if( $this->app_conf->get($hide_name) )
				$hide_props[ $f['name'] ] = TRUE;
		}

	/* check out entries */
		if( count($entries) )
		{
			for( $ii = (count($entries) - 1); $ii >= 0; $ii-- )
			{
				$e = $entries[$ii];
				if( ($e['distance_num'] > $within) && ($e['priority'] < $this->model->priority_always()) )
				{
					unset( $entries[$ii] );
				}
			}
		}

	/* no dealers found, check out distributors */
		$types = $this->model->get_types();
		if( (! count($entries)) && ($get_what == 'nearest') && $types )
		{
			$entries = $this->model->get_nearest( $lat, $long, 0, $search2, 1 );
			if( $entries )
			{
				// only the nearest
				$entries = array( $entries[0] );
			}
		}

		if( count($entries) )
		{
			$limit_output = $this->app_conf->get( 'limit_output' );
			if( $limit_output )
			{
				$limit_output = intval( $limit_output );
				if( $limit_output )
				{
					$entries = array_slice( $entries, 0, $limit_output );
				}
			}
		}

	/* build output */
		$out = array();
		$group_by = $this->app_conf->get( 'group_output' );

		if( count($entries) )
		{
			$final_entries = array();
			switch( $group_by )
			{
				case 'state':
					reset( $entries );
					foreach( $entries as $e )
					{
						if( ! isset($final_entries[$e['state']]) )
							$final_entries[$e['state']] = array( array() );
						$final_entries[$e['state']][0][] = $e;
					}
					ksort( $final_entries );
					break;

				case 'city':
					reset( $entries );
					foreach( $entries as $e )
					{
						if( ! isset($final_entries[$e['city']]) )
							$final_entries[$e['city']] = array( array() );
						$final_entries[$e['city']][0][] = $e;
					}
					ksort( $final_entries );
					break;

				case 'state_city':
					reset( $entries );
					foreach( $entries as $e )
					{
						if( ! isset($final_entries[$e['state']]) )
							$final_entries[$e['state']] = array();
						if( ! isset($final_entries[$e['state']][$e['city']]) )
							$final_entries[$e['state']][$e['city']] = array();

						$final_entries[$e['state']][$e['city']][] = $e;
					}
					ksort( $final_entries );
					$keys = array_keys( $final_entries );
					foreach( $keys as $k )
					{
						ksort( $final_entries[$k] );
					}
					break;

				case 'alphabetical':
					$final_entries[0][0] = $entries;
					usort( 
						$final_entries[0][0],
						create_function( '$a, $b', '{return strcmp($a["name"], $b["name"]);}' )
						);
					break;

				default:
					$final_entries[0][0] = $entries;
					break;
			}

			reset( $final_entries );
			foreach( $final_entries as $group2 => $entries2 )
			{
				if( $group2 )
				{
					$out[] = array(
						'id'		=> 0,
						'display'	=> $group2,
						'header'	=> 3,
						);
				}
				reset( $entries2 );
				foreach( $entries2 as $group3 => $entries3 )
				{
					if( $group3 )
					{
						$out[] = array(
							'id'		=> 0,
							'display'	=> $group3,
							'header'	=> 4,
							);
					}
					reset( $entries3 );
					foreach( $entries3 as $e )
					{
						$skip_display = $hide_props;
						$skip_display['priority'] = TRUE;
						if( $lat == '-all-' )
						{
							$skip_display['distance'] = TRUE;
							$skip_display['directions'] = TRUE;
						}

						$out[] = array(
							'id'		=> $e['id'],
							'name'		=> $e['name'],
							'priority'	=> $e['priority'],
							'display'	=> $this->display_location($e, $skip_display),
							'lat'		=> $e['latitude'],
							'lng'		=> $e['longitude'],
							'distance'	=> $e['distance_num'],
							);
					}
				}
			}
		}

		if( ! $out )
		{
			$measure = $this->app_conf->get( 'measurement' );
			$measure_title = lang('conf_measurement_') . $measure;

		// check if bigger within option exists
			$next_withins = array();
			reset( $within_options );
			foreach( $within_options as $wo )
			{
				if( $wo > $within )
				{
					$next_withins[] = $wo;
				}
			}

			if( $next_withins )
			{
				$more_entries = array();
				while( (! $more_entries) && $next_withins )
				{
					$next_within = array_shift($next_withins);
					$more_entries = $this->model->get_nearest( $lat, $long, $next_within, $search2 );
				}

				if( $more_entries )
				{
					$error = sprintf( lang('location_nothing_found'), $within . ' ' . $measure_title );
					$error .= '<br><a href="#" id="lpr-next-within" data-within="' . $next_within . '">' . sprintf( lang('location_nothing_found_suggest'), count($more_entries), $next_within . ' ' . $measure_title ) . '</a>';
				}
				else
				{
//					$error = sprintf( lang('location_nothing_found'), $next_within . ' ' . $measure_title );
					$error = $this->app_conf->get('not_found_text');
				}
			}
			else
			{
//				$error = sprintf( lang('location_nothing_found'), $within . ' ' . $measure_title );
				$error = $this->app_conf->get('not_found_text');
			}

			$out = array(
				'error'	=> $error,
				);
		}

		echo json_encode( $out );

	/* add log */
		if( $log_it )
		{
			$address = trim( $address );
			$this->log->add( 
				array(
					'address'	=> $address,
					'latitude'	=> $lat,
					'longitude'	=> $long,
					'search'	=> $str_search2
					)
				);
		}
		exit;
	}

	function index( $search2 = '' )
	{
		$search = '';
		if( isset($_GET['lpr-search']) )
		{
			$search = trim($_GET['lpr-search']);
		}
		elseif( isset($this->default_params['search']) )
		{
			$search = $this->default_params['search'];
		}

		if( ! strlen($search) )
		{
			$search = $this->default_search ? $this->default_search : $this->app_conf->get('default_search');
		}

		if( ! $search2 )
		{
			if( isset($this->default_params['search2']) )
			{
				$search2 = $this->default_params['search2'];
			}
		}

		if( ! $search2 )
		{
			if( isset($_GET['lpr-search2']) )
			{
				$search = $_GET['lpr-search2'];
			}
		}

	// product options
		$product_options = $this->app_conf->get('products');
		if( strlen($product_options) )
		{
			$product_options = explode( '||', $product_options );
		}
		else
		{
			$product_options = array();
		}

	// within options
		$within_options = array();
		$within = $this->app_conf->get('search_within');
		foreach( explode(',', $within) as $wo )
		{
			$wo = trim($wo);
			if($wo)
				$within_options[] = $wo;
		}
		sort( $within_options );

	// countries options
		$countries_options = array();
		$country = '';
		$choose_country = $this->app_conf->get('choose_country');
		if( $choose_country )
		{
			$countries = $this->model->get_countries();
			if( count($countries) > 1 )
			{
				if( isset($_GET['lpr-country']) )
				{
					$country = $_GET['lpr-country'];
				}
				else
				{
					$country = '';
				}

				$countries_options = array();
				$countries_options[''] = '- ' . lang('location_country') . ' -';
				foreach( $countries as $c )
				{
					$countries_options[$c] = $c;
				}
			}
		}

		$this->data['search'] = $search;
		$this->data['product_options'] = $product_options;

		$this->data['countries_options'] = $countries_options;
		$this->data['country'] = $country;

		$this->data['search2'] = $search2;
		$this->data['show_sidebar'] = $this->app_conf->get('show_sidebar');
		$this->data['include'] = 'front';
		$this->data['within_options'] = $within_options;

	// check if custom front view file exists
		$layout_file = APPPATH . 'views/' . 'front_layout.php';
		$this->data['layout_file'] = $layout_file;

		$this->load->view( $this->template, $this->data );
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */