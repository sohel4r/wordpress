<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conf extends Admin_controller
{
	private $params = array();

	function __construct()
	{
		parent::__construct();

		$this->params = array(
			'settings' => array(
				'default_search',
				'search_label',
				'search_button',
				'append_search',
				'autodetect_button',
				'your_location_label',
				'start_listing',
				'csv_separator',
				'measurement',
				'search_within',
				'show_sidebar',
				'show_distance',
				'trigger_autodetect',
				'not_found_text',
				'group_output',
				'limit_output',
				'language',
				'choose_country',
				),
			'form' => array(
				'form_misc1',
				'form_misc2',
				'form_misc3',
				'form_misc4',
				'form_misc5',
				'form_misc1_hide',
				'form_misc2_hide',
				'form_misc3_hide',
				'form_misc4_hide',
				'form_misc5_hide',
				'form_products',
				),
			);

		$this->data['defaults'] = array();
		reset( $this->params );
		foreach( $this->params as $pk => $pa ){
			reset( $pa );
			foreach( $pa as $p ){
				$this->data['defaults'][$p] = $this->app_conf->get($p);
				}
			}
	}

	function reset( $what )
	{
		// update
		reset( $this->params[$what] );
		foreach( $this->params[$what] as $p ){
			$this->app_conf->reset( $p, $v );
			}

	// redirect back
		$this->session->set_flashdata( 'message', lang('common_ok') );
		ci_redirect( 'admin/conf/' . $what );
	}

	function resetproducts()
	{
		$this->app_conf->set('products', '');
		$this->session->set_flashdata( 'message', lang('common_ok') );
		ci_redirect( 'admin/conf/settings' );
	}

	function index( $what = 'settings' )
	{
		if( $what == 'resetproducts' )
		{
			return $this->resetproducts();
		}

		if( $this->form_validation->run('conf-' . $what) == false ){
		// display the form
			$this->data['include'] = 'admin/conf/' . $what;
			$this->load->view( $this->template, $this->data );
			}
		else {
		// update
			reset( $this->params[$what] );
			foreach( $this->params[$what] as $p ){
				$v = $this->input->post( $p );
				$this->app_conf->set( $p, $v );
				}

		// redirect back
			$msg = lang('common_update') . ': ' . lang('common_ok');
			$this->session->set_flashdata( 'message', $msg );
			ci_redirect( 'admin/conf/' . $what );
			}
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */