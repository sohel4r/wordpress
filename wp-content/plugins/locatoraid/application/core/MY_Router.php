<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH."third_party/MX/Router.php";

//class MY_Router extends CI_Router {
class MY_Router extends MX_Router {
	function _set_request ($seg = array())
	{
		parent::_set_request(str_replace('-', '_', $seg));
	}
}
