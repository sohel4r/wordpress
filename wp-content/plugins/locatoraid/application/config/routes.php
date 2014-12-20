<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require( dirname(__FILE__) . '/_app.php' );

$route['default_controller'] = isset($GLOBALS['NTS_CONFIG'][$app]['DEFAULT_CONTROLLER']) ? $GLOBALS['NTS_CONFIG'][$app]['DEFAULT_CONTROLLER'] : 'admin/locations';

$route['front/get/(:any)/(:any)'] = 'front/get/$1/$2';

$route['front/start/(:any)/(:any)'] = 'front/start/$1/$2';
$route['front/start/(:any)'] = 'front/start/$1';
$route['front/start'] = 'front/index';

$route['front/(:any)'] = 'front/start/$1';

$route['load/(:any)/(:any)'] = 'load/index/$1/$2';
$route['load/(:any)'] = 'load/index/$1';

$route['admin/locations/(:num)'] = 'admin/locations/index/$1';
$route['admin/locations/search/(:any)/(:num)'] = 'admin/locations/index/$2/$1';
$route['admin/locations/search/(:any)'] = 'admin/locations/index//$1';

$route['admin/conf/(:any)/reset'] = 'admin/conf/reset/$1';
$route['admin/conf/(:any)'] = 'admin/conf/index/$1';
$route['pro/admin/conf/(:any)'] = 'pro/admin/conf/index/$1';

$route['pro/admin/stats/shortcut'] = 'pro/admin/stats/shortcut';
$route['pro/admin/stats/(:any)'] = 'pro/admin/stats/index/$1';

$route['404_override'] = '';

$route['locazip/admin/companies/(:num)'] = 'locazip/admin/companies/index/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */	