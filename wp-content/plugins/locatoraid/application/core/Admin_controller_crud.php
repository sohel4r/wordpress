<?php
class Admin_controller_crud extends Admin_controller
{
	protected $per_page;
	protected $no_load_view;
	public $fields;

	function __construct()
	{
/*		
$this->conf = array(
	'model'			=> 'Category_model',
	'path'			=> 'admin/categories',
	'validation'	=> 'category',
	);
	
*/
		parent::__construct();
		if( ! isset($this->conf) )
			$this->conf = array();

		$this->per_page = 0;
		$this->no_load_view = FALSE;
		$this->fields = array(
			array(
				'name'	=> 'name',
				'title'	=> lang('location_name'),
				'size'	=> 24
				),
			);

		$model = $this->conf['model'];
		$this->load->model( $model, 'model' );
	}

	protected function _parse_search( $search )
	{
		$this->model->like( $search );
		return $search;
	}

	function index(){
		$args = func_get_args();
		if( count($args) == 2 )
		{
			$page = $args[0];
			$search = $args[1];
		}
		elseif( count($args) == 1 )
		{
			$page = $args[0];
			$search = '';
		}
		else
		{
			$page = 1;
			$search = '';
		}
		if( ! $page )
			$page = 1;
		if( $search )
			$search = urldecode( $search );

		$show_search = $search;

		if( $this->per_page )
		{
			$total_count = $this->model->count_all();
			if( $search )
			{
				$show_search = $this->_parse_search( $search );
			}
			$matched_count = $this->model->count_all();

			$pager_config = array(
				'per_page' 		=> $this->per_page,
				'total_rows'	=> $matched_count,
				);

			if( $search ){
				$pager_config['uri_segment'] = 5;
				$pager_config['base_url'] = ci_site_url( array($this->conf['path'], 'search', $search) );
				}
			else {
				$pager_config['uri_segment'] = 3;
				$pager_config['base_url'] = ci_site_url( $this->conf['path'] );
				}
			$this->pagination->initialize($pager_config);

			$this->model->db->offset( ($page - 1) * $this->per_page );
			$this->model->db->limit( $this->per_page );
		}

		if( $search )
		{
			$show_search = $this->_parse_search( $search );
		}

		$this->data['search'] = $search;
		$this->data['show_search'] = $show_search;
		$this->data['total_count'] = $total_count;
		$this->data['matched_count'] = $matched_count;
		$this->data['entries'] = $this->model->get_all();
		$this->data['include'] = $this->conf['path'] . '/index';
		if( ! $this->no_load_view )
			$this->load->view( $this->template, $this->data);
		}

	protected function _prepare_export()
	{
		$separator = $this->app_conf->get( 'csv_separator' );

	// header
		$headers = array();
		reset( $this->fields );
		foreach( $this->fields as $f )
		{
			$headers[] = $f['name'];
		}

		$data = array();
		$data[] = join( $separator, $headers );

	// entries
		$entries = $this->model->get_all( $headers );
		reset( $entries );
		foreach( $entries as $e )
		{
			$data[] = hc_build_csv( array_values($e), $separator );
		}
		return $data;
	}

	function _glue_export( $data )
	{
	}
	
	protected function _push_export( $data )
	{
	// output
		$out = join( "\n", $data );

		$file_name = isset( $this->conf['export'] ) ? $this->conf['export'] : 'export';
		$file_name .= '-' . date('Y-m-d_H-i') . '.csv';

		$this->load->helper('download');
		force_download($file_name, $out);
		exit;
	}

	function export()
	{
		$data = $this->_prepare_export();
		$this->_push_export( $data );
	}

	function search()
	{
		$search = $this->input->post( 'search' );
		$search = trim( $search );
		if( $search ){
			$search = urlencode( $search );
			ci_redirect( $this->conf['path'] . '/search/' . $search );
			}
		else {
			ci_redirect( $this->conf['path'] );
			}
		exit;
	}

	function delete($id)
	{
		$this->model->delete( $id );
	// redirect to list
		$this->session->set_flashdata( 'message', lang('common_delete') . ': ' . lang('common_ok') );
		ci_redirect( $this->conf['path'] );
		exit;
	}

	function add()
	{
		if( $this->form_validation->run( $this->conf['validation'] ) == false ){
		// display the form
			$path_add = isset($this->conf['path-add']) ? $this->conf['path-add'] : $this->conf['path'] . '/add';
			if( $path_add == $this->conf['path'] ){
				return $this->index();
				}
			else {
				$this->data['include'] = $path_add;
				}
			}
		else {
		// add
			$object = array();
			reset( $this->fields );
			foreach( $this->fields as $f )
			{
				$object[ $f['name'] ] = $this->input->post( $f['name'] );
			}
			$new_id = $this->model->save( $object );

		// redirect to list
			$this->session->set_flashdata( 'message', lang('common_add') . ': ' . lang('common_ok') );
			ci_redirect( array($this->conf['path'], 'edit', $new_id) );
			exit;
			}
		$this->load->view( $this->template, $this->data);
	}

	function edit( $id )
	{
		$object = $this->model->get($id);
		if( ! $object ){
			$this->session->set_flashdata( 'message', sprintf(lang('not_found'), $id) );
			ci_redirect( $this->conf['path'] );
			exit;
			}

		if( $this->form_validation->run( $this->conf['validation'] ) == false ){
		// display the form
			$this->data['object'] = $object;
			$this->data['include'] = $this->conf['path'] . '/edit';
			}
		else {
		// update
			$adata = array();
			reset( $this->fields );
			foreach( $this->fields as $f )
			{
				$adata[ $f['name'] ] = $this->input->post( $f['name'] );
			}
			$object = array_merge( $object, $adata );

			$this->model->save( $object );
		// redirect to list
			$this->session->set_flashdata( 'message', lang('common_update') . ': ' . lang('common_ok') );
			ci_redirect( array($this->conf['path'], 'edit', $id) );
			exit;
			}
		$this->load->view( $this->template, $this->data);
	}
}
