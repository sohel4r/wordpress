<?php
Class MY_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function like( $what )
	{
		$fields = $this->get_fields();
		reset( $fields );
		foreach( $fields as $f )
		{
			$this->db->or_like( $f['name'], $what );
		}
	}
}