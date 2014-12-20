<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Form_validation extends CI_Form_validation {
	public function is_unique($str, $field)
	{
		list($table, $field)=explode('.', $field);

		$sql = 'SHOW KEYS FROM ' . $this->CI->db->dbprefix($table) . " WHERE Key_name = 'PRIMARY'";
		$q = $this->CI->db->query($sql)->row();
		$primary_key = $q->Column_name;

		if($this->CI->input->post($primary_key) > 0) {
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str,$primary_key.' !='=>$this->CI->input->post($primary_key)));
			}
		else {
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
			}
	return $query->num_rows() === 0;
	}

	public function numeric_comma($str)
	{
		return (bool)preg_match( '/^[\-+]?[0-9\, ]*\.?[0-9\, ]+$/', $str);
	}
}