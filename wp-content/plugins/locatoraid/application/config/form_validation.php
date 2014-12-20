<?php
$config = array(
	'conf-settings' => array(
		array(
			'field'   => 'csv_separator',
			'label'   => 'lang:conf_csv_separator',
			'rules'   => 'trim|required'
			),
		array(
			'field'   => 'search_within',
			'label'   => 'lang:conf_search_within',
//			'rules'   => 'trim|required|numeric|greater_than[0]'
			'rules'   => 'trim|required|numeric_comma'
			),
		),

	'conf-notification' => array(
		array(
			'field'   => 'notification_subject',
			'label'   => 'lang:notification_subject',
			'rules'   => 'trim|required'
			),
		array(
			'field'   => 'notification_body',
			'label'   => 'lang:notification_body',
			'rules'   => 'trim|required'
			),
		),

	'conf-form' => array(
		array(
			'field'   => 'form_misc1',
			'rules'   => 'trim'
			),
	   array(
			'field'   => 'form_misc2',
			'rules'   => 'trim'
			),
	   array(
			'field'   => 'form_misc3',
			'rules'   => 'trim'
			),
	   array(
			'field'   => 'form_misc4',
			'rules'   => 'trim'
			),
	   array(
			'field'   => 'form_misc5',
			'rules'   => 'trim'
			),
		),

	'location' => array(
		array(
			'field'   => 'name',
			'label'   => 'lang:location_name',
			'rules'   => 'trim|required'
			),
		array(
			'field'   => 'street1',
			'label'   => 'lang:location_street1',
			'rules'   => 'trim|required'
			),
		),
	);
