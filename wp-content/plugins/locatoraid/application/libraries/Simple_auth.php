<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Simple_auth
{
	public function __construct()
	{
		$this->load->library( array('email', 'session') );
		$this->load->helper('cookie');

		$this->load->config('simple_auth', TRUE);
		$this->load->model('simple_auth_model', 'auth_model');

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code'))
		{
			$this->auth_model->login_remembered_user();
		}
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->auth_model, $method) )
		{
			throw new Exception('Undefined method Simple_auth::' . $method . '() called');
		}

		return call_user_func_array( array($this->auth_model, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return ci_get_instance()->$var;
	}

	public function get_userdata()
	{
		$return = $this->session->all_userdata();
		return $return;
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$identity = $this->config->item('identity', 'simple_auth');
		$this->session->unset_userdata($identity);
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('user_id');

		//delete the remember me cookies if they exist
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		if (get_cookie('remember_code'))
		{
			delete_cookie('remember_code');
		}

		//Recreate the session
		$this->session->sess_destroy();
		$this->session->sess_create();

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$identity = $this->config->item('identity', 'simple_auth');
		if( defined('LPR_ADMIN') && LPR_ADMIN )
			return 1;
		else
			return (bool) $this->session->userdata($identity);
	}

	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin($id=false)
	{
		return $this->logged_in();
	}

	/**
	 * forgotten password feature
	 *
	 * @return mixed  boolian / array
	 * @author Mathew
	 **/
	public function forgotten_password( $email )
	{
		$admin_email = $this->app_conf->get('admin_email');
		if( $admin_email == $email )
		{
			$new_password = $this->auth_model->salt();
			$this->change_password( $new_password );

			// generate new password and send
			$this->load->library( 'email/hc_email' );
			$mailer = new Hc_email;

			$mailer->from = $admin_email;
			$mailer->fromName = $admin_email;

			$data = array(
				'identity'		=> $admin_email,
				'new_password'	=> $new_password,
				);
			$body = $this->load->view( 'auth/email/new_password.tpl.php', $data, true);
			$subj = $this->lang->line('auth_email_new_password_subject');

			$mailer->setSubject( $subj );
			$mailer->setBody( $body );
			$mailer->sendToOne( $admin_email );

			$this->set_message('auth_forgot_password_successful');
			return TRUE;
		}
		else
		{
			$this->set_error('auth_forgot_password_unsuccessful');
			return FALSE;
		}
	}
}