<?php
class Admin_controller extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
		if ( ! $this->migration->current()){
			show_error($this->migration->error_string());
			return false;
			}
		$this->load->library( 'app_conf' );

		$app = $this->config->item('nts_app');
		if ( ! $this->auth->logged_in() )
		{
			if( isset($GLOBALS['NTS_CONFIG'][$app]['FORCE_LOGIN_ID']) && isset($GLOBALS['NTS_CONFIG'][$app]['FORCE_LOGIN_NAME']) )
			{
				$id = $GLOBALS['NTS_CONFIG'][$app]['FORCE_LOGIN_ID'];
				$email = $GLOBALS['NTS_CONFIG'][$app]['FORCE_LOGIN_NAME'];
				$this->auth->do_login( $id, $email );
			}
			else
			{
				ci_redirect('auth/login');
			}
		}
		elseif ( ! $this->auth->is_admin() )
		{
			$this->session->set_flashdata('message', 'You must be an admin to view this page');
			ci_redirect('auth/login');
		}

	// validation error
		$this->form_validation->set_message( 'is_unique', lang('common_err_already_registered') );
	
	// template
		$this->template = $this->input->is_ajax_request() ? 'admin/template_ajax' : 'admin/template';
	}
}
