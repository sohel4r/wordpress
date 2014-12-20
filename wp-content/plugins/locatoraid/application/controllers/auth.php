<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Front_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('form_validation');

		$is_admin = ( $this->auth->logged_in() && $this->auth->is_admin() ) ? TRUE : FALSE;
		if( $is_admin )
			$this->template = $this->input->is_ajax_request() ? 'admin/template_ajax' : 'admin/template';
		else {
			$this->template = $this->input->is_ajax_request() ? 'admin/template_ajax' : 'admin/template';
			}
	}

	function index()
	{
		if( ! $this->auth->logged_in() )
		{
			//redirect them to the login page
			ci_redirect('auth/login', 'refresh');
		}
		return;
	}

	//log the user in
	function login()
	{
		$this->data['title'] = "Login";

	//validate form input
		$this->form_validation->set_rules('identity', 'lang:common_email', 'required');
		$this->form_validation->set_rules('password', 'lang:common_password', 'required');

		if ($this->form_validation->run() == true)
		{
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->auth->messages());
				ci_redirect('/', 'refresh');
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('error', $this->auth->errors());
				ci_redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
		//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['auth_message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);

			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);

			$this->data['include'] = 'auth/login';
			$this->load->view( $this->template, $this->data);
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->auth->messages());
		ci_redirect('auth/login', 'refresh');
	}

	//change password
	function profile()
	{
		$email = $this->app_conf->get( 'admin_email' );

		$this->form_validation->set_rules('email', lang('common_email'), 'required');
		$new_password = $this->input->post('new');
		if( $new_password  )
		{
			$this->form_validation->set_rules('new', lang('common_new_password'), 'required|min_length[' . $this->config->item('min_password_length', 'simple_auth') . ']|max_length[' . $this->config->item('max_password_length', 'simple_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', lang('common_new_password_confirm'), 'required');
			}

		if ( ! $this->auth->logged_in())
		{
			ci_redirect('auth/login', 'refresh');
		}

		$this->data['defaults'] = array('email' => $email);

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->data['email'] = array(
				'name' => 'email',
				'id'   => 'email',
				'type' => 'text',
			);

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'simple_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
			);

			//render
			$this->data['include'] = 'auth/profile';
			$this->load->view( $this->template, $this->data);
		}
		else
		{
			$msg = array();
			$new_email = $this->input->post('email');
			if( $new_email != $email ){
				$this->app_conf->set( 'admin_email', $new_email );
				$msg[] = lang('profile_updated');
				}

			$new_password = $this->input->post('new');
			if( $new_password ){
				$change = $this->auth->change_password($new_password);
				if ($change)
				{
					$msg[] = lang('auth_password_change_successful');
				}
				else
				{
					$msg[] = $this->auth->errors();
				}
			}

			$msg = join( '<br/>', $msg );
			$this->session->set_flashdata('message', $msg);
			ci_redirect('auth/profile');
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			//set any errors and display the form
			$this->data['auth_message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['include'] = 'auth/forgot_password';
			$this->load->view( $this->template, $this->data);
		}
		else
		{
			$supplied_email = $this->input->post('email');
			//run the forgotten password method to email new one to the user
			$forgotten = $this->auth->forgotten_password($supplied_email);

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->auth->messages());
				ci_redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->auth->errors());
				ci_redirect("auth/forgot_password", 'refresh');
			}
		}
	}
}
