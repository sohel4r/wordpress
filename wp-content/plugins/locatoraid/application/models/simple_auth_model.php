<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simple_auth_model extends MY_Model
{
	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 **/
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors;

	public function __construct()
	{
		parent::__construct();
		$this->load->config('simple_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');

		//initialize hash method
		$this->hash_method = 'sha1';
		$this->salt_length = 10;

		//initialize messages and error
		$this->messages = array();
		$this->errors = array();
	}

	/**
	 * Misc functions
	 *
	 * Hash password : Hashes the password to be stored in the database.
	 * Hash password db : This function takes a password and validates it
	 * against an entry in the users table.
	 * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */

	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}

		$salt = $this->salt();
		return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
	}

	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password_db($password, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}

		$hash_password_db = $this->app_conf->get('admin_password');

		// sha1
		$salt = substr($hash_password_db, 0, $this->salt_length);
		$db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);

		if($db_password == $hash_password_db)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * Generates a random salt value.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
	}

	/**
	 * change password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function change_password( $new )
	{
		$new_password = $this->hash_password($new);
		$this->app_conf->set( 'admin_password', $new_password );
		return TRUE;
	}

	/**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function login($identity, $password)
	{
		if (empty($identity) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$admin_email = $this->app_conf->get('admin_email');
		$hash_password_db = $this->app_conf->get('admin_password');

		if ( $admin_email != $identity )
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}

		$password = $this->hash_password_db($password);
		if ($password === TRUE)
		{
			$user_id = 1;
			$this->do_login( $user_id, $admin_email );
			return TRUE;
		}

	//Hash something anyway, just to take up time
		$this->hash_password($password);
		$this->set_error('login_unsuccessful');
		return FALSE;
	}

	public function do_login( $user_id, $admin_email )
	{
		$session_data = array(
			'identity'             => $admin_email,
			'username'             => $admin_email,
			'email'                => $admin_email,
			'user_id'              => $user_id
			);
		$this->session->set_userdata($session_data);
		$this->set_message('login_successful');
		return TRUE;
	}

	/**
	 * set_lang
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function set_lang($lang = 'en')
	{
		// if the user_expire is set to zero we'll set the expiration two years from now.
		if($this->config->item('user_expire', 'simple_auth') === 0)
		{
			$expire = (60*60*24*365*2);
		}
		// otherwise use what is set
		else
		{
			$expire = $this->config->item('user_expire', 'simple_auth');
		}

		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $expire
		));

		return TRUE;
	}

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;
		return $message;
	}

	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
//			$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			$_output .= $messageLang;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
//				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
				$_output[] = $messageLang;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}

	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;
		return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
//			$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			$_output .= $errorLang;
		}

		return $_output;
	}

	/**
	 * errors as array
	 *
	 * Get the error messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function errors_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->errors as $error)
			{
				$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
//				$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
				$_output[] = $errorLang;
			}
			return $_output;
		}
		else
		{
			return $this->errors;
		}
	}
}
