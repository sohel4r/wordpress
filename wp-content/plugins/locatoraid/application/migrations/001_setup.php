<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_setup extends CI_Migration
{
	public function up()
	{
	// conf
		$this->dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
				'name' => array(
					'type' => 'VARCHAR(32)',
					'null' => FALSE,
					),
				'value' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				)
			);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('conf');

	// locations
		$this->dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
					),
				'name' => array(
					'type' => 'VARCHAR(100)',
					'null' => FALSE,
					),
				'street1' => array(
					'type' => 'VARCHAR(100)',
					'null' => FALSE,
					),
				'street2' => array(
					'type' => 'VARCHAR(100)',
					'null' => FALSE,
					),

				'city' => array(
					'type' => 'VARCHAR(50)',
					'null' => TRUE,
					),
				'state' => array(
					'type' => 'VARCHAR(20)',
					'null' => TRUE,
					),
				'zip' => array(
					'type' => 'VARCHAR(20)',
					'null' => TRUE,
					),
				'country' => array(
					'type' => 'VARCHAR(50)',
					'null' => TRUE,
					),

				'phone' => array(
					'type' => 'VARCHAR(30)',
					'null' => TRUE,
					),
				'website' => array(
					'type' => 'VARCHAR(100)',
					'null' => TRUE,
					),


				'misc1' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				'misc2' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					),
				'misc3' => array(
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
				)
			);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('locations');

		if( Modules::exists('locazip') )
		{
		// companies
			$this->dbforge->add_field(
				array(
					'id' => array(
						'type' => 'INT',
						'null' => FALSE,
						'unsigned' => TRUE,
						'auto_increment' => TRUE
						),
					'name' => array(
						'type' => 'VARCHAR(100)',
						'null' => FALSE,
						),
					)
				);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('companies');

	// companies to locations
			$this->dbforge->add_field(
				array(
					'id' => array(
						'type' => 'INT',
						'null' => FALSE,
						'unsigned' => TRUE,
						'auto_increment' => TRUE
						),
					'company_id' => array(
						'type' => 'INT',
						'null' => FALSE,
						'unsigned' => TRUE,
						),
					'location_id' => array(
						'type' => 'INT',
						'null' => FALSE,
						'unsigned' => TRUE,
						),
					)
				);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('companies_locations');
		}
	}

	public function down()
	{
		$this->dbforge->drop_table('conf');
		$this->dbforge->drop_table('locations');
		if( Modules::exists('locazip') )
		{
			$this->dbforge->drop_table('companies');
			$this->dbforge->drop_table('companies_locations');
		}
	}
}
