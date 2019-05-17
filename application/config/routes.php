<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'main';

$route['(:any)'] = "main/world/$1";
$route['create_world'] = "main/create_world/$1";

// Cron
$route['cron/(:any)'] = "cron/index/$1";

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;