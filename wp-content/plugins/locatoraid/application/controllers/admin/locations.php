<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Locations extends Admin_controller_crud
{
	function __construct()
	{
		$this->conf = array(
			'model'			=> 'Location_model',
			'path'			=> 'admin/locations',
			'path-add'		=> 'admin/locations/add',
			'validation'	=> 'location',
			'export'		=> 'locations',
			);
		parent::__construct();

		$this->per_page = 12;
		$this->fields = $this->model->get_fields();
	}

	protected function _parse_search( $search )
	{
		switch( $search )
		{
			case '_failed_':
				$this->model->condition_failed();
				$return = '';
				break;
			case '_notyet_':
				$this->model->condition_not_yet();
				$return = '';
				break;
			default:
				$return = parent::_parse_search( $search );
				break;
		}
	return $return;
	}

	function index()
	{
		$this->no_load_view = TRUE;
		$args = func_get_args();
		if( count($args) == 2 )
		{
			parent::index( $args[0], $args[1] );
		}
		elseif( count($args) == 1 )
		{
			parent::index( $args[0] );
		}
		else
		{
			parent::index();
		}
		$this->no_load_view = FALSE;

		for( $ii = 0; $ii < count($this->data['entries']); $ii++ )
		{
			$e = $this->data['entries'][$ii];
			$this->data['entries'][$ii]['view'] = $this->display_location( 
				$e,
				array(
					'name' => TRUE,
					'directions' => TRUE
					)
				);
		}

		$this->model->condition_failed();
		$failed_count = $this->model->count_all();
		$this->data['failed_count'] = $failed_count;

		$this->model->condition_not_yet();
		$not_yet_count = $this->model->count_all();
		$this->data['not_yet_count'] = $not_yet_count;

		$this->load->view( $this->template, $this->data);
	}

	function geocode_get( $id = 0 )
	{
		if( $id )
		{
			$this->model->db->where( array('id' => $id) );
			$this->model->db->limit(1);
		}
		else
		{
			$this->model->condition_not_yet();
			$this->model->db->limit(1);
		}
		$entries = $this->model->get_all();

		$this->model->condition_not_yet();
		$left = $this->model->count_all();

		$out = array();
		reset( $entries );
		foreach( $entries as $e )
		{
			$out = array(
				'id'		=> $e['id'],
				'address'	=> $this->model->make_address( $e, FALSE, FALSE ),
				'left'		=> $left,
				);
		}
		echo json_encode( $out );
		exit;
	}

	function geocode_save( $id, $latitude, $longitude )
	{
		$latitude = str_replace( '_', '-', $latitude );
		$longitude = str_replace( '_', '-', $longitude );

		$object = array(
			'id'		=> $id,
			'latitude'	=> $latitude,
			'longitude'	=> $longitude
			);
		$this->model->save( $object, TRUE );
	}

	function geocode()
	{
		$this->data['include'] = $this->conf['path'] . '/geocode';
		$this->load->view( $this->template, $this->data);
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */