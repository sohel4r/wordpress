<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_type extends CI_Migration {
	public function up()
	{
		$this->dbforge->add_column(
			'locations',
			array(
				'loc_type' => array(
					'type' 		=> 'INT',
					'null'		=> FALSE,
					'default'	=> 0
					),
				)
			);
	}

	public function down()
	{
		$this->dbforge->drop_column('locations', 'loc_type');
	}
}
