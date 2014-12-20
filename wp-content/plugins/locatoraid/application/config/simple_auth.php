<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 | -------------------------------------------------------------------------
 | Authentication options.
 | -------------------------------------------------------------------------
 | maximum_login_attempts: This maximum is not enforced by the library, but is
 | used by $this->ion_auth->is_max_login_attempts_exceeded().
 | The controller should check this function and act
 | appropriately. If this variable set to 0, there is no maximum.
 */
$config['identity']             = 'email'; 				// A database column which is used to login with
$config['min_password_length']  = 4; 					// Minimum Required Length of Password
$config['max_password_length']  = 20; 					// Maximum Allowed Length of Password
$config['track_login_attempts'] = FALSE;				// Track the number of failed login attempts for each user or ip.
$config['maximum_login_attempts']     = 3; 				// The maximum number of failed login attempts.
$config['lockout_time'] = 600;				   			// The number of seconds to lockout an account due to exceeded attempts
$config['forgot_password_expiration'] = 0; 				// The number of seconds after which a forgot password request will expire. If set to 0, forgot password requests will not expire.

/*
 | -------------------------------------------------------------------------
 | Forgot Password Email Template
 | -------------------------------------------------------------------------
 | Default: forgot_password.tpl.php
 */
$config['email_forgot_password'] = 'forgot_password.tpl.php';

/*
 | -------------------------------------------------------------------------
 | Message Delimiters.
 | -------------------------------------------------------------------------
 */
$config['message_start_delimiter'] = '<p>'; 	// Message start delimiter
$config['message_end_delimiter']   = '</p>'; 	// Message end delimiter
$config['error_start_delimiter']   = '<p>';		// Error mesage start delimiter
$config['error_end_delimiter']     = '</p>';	// Error mesage end delimiter

/* End of file ion_auth.php */
/* Location: ./application/config/ion_auth.php */
