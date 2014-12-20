<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_log extends CI_Migration {
	public function up()
	{
		$this->dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
				'created_at' => array(
					'type' => 'INT',
					'null' => FALSE,
					),
				'address' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				'latitude' => array(
					'type' => 'DOUBLE',
					'null' => FALSE,
					'default'	=> 0,
					),
				'longitude' => array(
					'type' => 'DOUBLE',
					'null' => FALSE,
					'default'	=> 0,
					),
				'search' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				'remote' => array(
					'type' => 'VARCHAR(128)',
					'null' => TRUE,
					),
				)
			);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('log');
	}

	public function down()
	{
		$this->dbforge->drop_table('log');
	}
}
