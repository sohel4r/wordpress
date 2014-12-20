<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_priority extends CI_Migration {
	public function up()
	{
		$this->dbforge->add_column(
			'locations',
			array(
				'priority' => array(
					'type' 		=> 'INT',
					'null'		=> FALSE,
					'default'	=> 0
					),
				)
			);
	}

	public function down()
	{
		$this->dbforge->drop_column('locations', 'priority');
	}
}
