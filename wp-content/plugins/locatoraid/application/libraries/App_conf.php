<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_conf
{
	protected $saved = array();

	public function __construct()
	{
		$this->load->model('app_conf_model');
		$this->config->load('defaults', TRUE);
		$this->saved = $this->app_conf_model->get_all();
	}

	function get_customer_fields()
	{
		$return = array();
		$all_fields = array('first_name', 'last_name', 'email', 'phone', 'company', 'address1', 'address2', 'city', 'state', 'zip', 'country' );
		reset( $all_fields );
		foreach( $all_fields as $f ){
			$value = $this->get( 'form_' . $f );
			if( $value != HC_OPTION_NO ){
				$return[ $f ] = $value;
				}
			}
		return $return;
	}

	public function get( $pname )
	{
		if( isset($this->saved[$pname]) )
		{
			$return = $this->saved[$pname];
		}
		else
		{
			$return = $this->config->item( $pname, 'defaults' );
		}
	return $return;
	}

	public function set( $pname, $pvalue )
	{
		$this->saved[ $pname ] = $pvalue;
		return $this->app_conf_model->save( $pname, $pvalue );
	}

	public function reset( $pname )
	{
		return $this->app_conf_model->delete( $pname );
	}

	function get_loaded_names()
	{
		$return = array_keys( $this->saved );
		return $return;
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
}
