<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_moremisc extends CI_Migration {
	public function up()
	{
		$this->dbforge->add_column(
			'locations',
			array(
				'misc4' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				'misc5' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				)
			);
	}

	public function down()
	{
		$this->dbforge->drop_column('locations', 'misc4');
		$this->dbforge->drop_column('locations', 'misc5');
	}
}
