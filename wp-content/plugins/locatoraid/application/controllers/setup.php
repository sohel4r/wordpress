<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends MX_Controller {
	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper( array('url') );
		$this->data = array();
		$this->data['page_title'] = $this->config->item('nts_app_title') . ' Installation';

		$this->load->helper( array('language', 'form') );
		$this->load->helper( array('hitcode') );
		$this->load->library( array('form_validation', 'session') );
		$this->form_validation->set_error_delimiters('<div class="hc-form-error">', '</div>');

	// table
		$this->load->library('table');
		$table_tmpl = array (
			'table_open'          => '<table class="table table-striped">',
			);
		$this->table->set_template( $table_tmpl );

	// conf
		$this->load->library( 'simple_auth', NULL, 'auth' );
		$app_core = $this->config->item('nts_app_core') ? $this->config->item('nts_app_core') : $this->config->item('nts_app');

		$my_language = 'english';
		$this->lang->load( $app_core, $my_language );
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['error'] = $this->session->flashdata('error');
	}

	function index()
	{
	// check if already setup
		if( $this->is_setup() )
		{
			if ( ! $this->auth->logged_in() ){
				ci_redirect('auth/login');
				}
			elseif ( ! $this->auth->is_admin() ){
				$this->session->set_flashdata('message', 'You must be an admin to view this page');
				ci_redirect('auth/login');
				}
		}
		$this->data['include'] = 'setup';
		$this->load->view( 'template', $this->data);

		$app = $this->config->item('nts_app');
		$predefined_admin = isset($GLOBALS['NTS_CONFIG'][$app]['PREDEFINED_ADMIN']) ? $GLOBALS['NTS_CONFIG'][$app]['PREDEFINED_ADMIN'] : NULL;
		if( $predefined_admin )
		{
			$this->run( $predefined_admin );
		}
	}

	function run( $predefined_admin = NULL )
	{
		$validation = array(
		   array(
				'field'   => 'email',
				'label'   => 'lang:email',
				'rules'   => 'trim|required|valid_email'
				),
		   array(
				'field'   => 'password',
				'label'   => 'lang:password',
				'rules'   => 'trim|required|matches[password2]'
				),
		   array(
				'field'   => 'password2',
				'label'   => 'lang:password2',
				'rules'   => 'trim|required'
				),
			);
		$this->form_validation->set_rules( $validation );

		if( (! $predefined_admin) && ($this->form_validation->run() == FALSE) )
		{
			$this->data['include'] = 'setup';
			$this->load->view( 'template', $this->data);
		}
		else
		{
			if( (! $predefined_admin) )
			{
				$admin_email = $this->input->post( 'email' );
				$admin_password = $this->input->post( 'password' );
			}
			else
			{
				$admin_email = $predefined_admin;
				$admin_password = mt_rand(1000000, 9999999);
			}

			$tables = array();
			$sth = $this->db->query("SHOW TABLES LIKE '" . NTS_DB_TABLES_PREFIX . "%'");
			foreach ($sth->result_array() as $r) {
				reset( $r );
				foreach( $r as $k => $v ){
					$tables[] = $v;
					}
				}
			reset( $tables );
			foreach( $tables as $t )
			{
				$this->db->query("DROP TABLE " . $t . "");
			}

		$this->load->library('migration');
		if ( ! $this->migration->current()){
			show_error($this->migration->error_string());
			return false;
			}

		$this->load->library( 'app_conf' );

	// create admin
		$this->app_conf->set( 'admin_email', $admin_email );
		$hash_password = $this->auth->hash_password( $admin_password );
		$this->app_conf->set( 'admin_password', $hash_password );
		$setup_ok = TRUE;

		if( $setup_ok )
		{
		/* default settings */
			$this->app_conf->set( 'email_from',			$admin_email );
			$this->app_conf->set( 'email_from_name',	$admin_email );

			$this->session->set_flashdata( 'message', lang('ok') );
			ci_redirect( 'setup/ok' );
			return;
		}
		ci_redirect( '' );
		}
	}

	function ok()
	{
		$this->data['include'] = 'setup_ok';
		$this->load->view( 'template', $this->data);
	}

	function is_setup()
	{
		$return = TRUE;
		if( $this->db->table_exists('conf') ){
			$return = TRUE;
			}
		else {
			$return = FALSE;
			}
		return $return;
	}
}

/* End of file setup.php */
/* Location: ./application/controllers/setup.php */