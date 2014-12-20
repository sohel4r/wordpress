<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_products extends CI_Migration {
	public function up()
	{
		$this->dbforge->add_column(
			'locations',
			array(
				'products' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				)
			);
	}

	public function down()
	{
		$this->dbforge->drop_column('locations', 'products');
	}
}
