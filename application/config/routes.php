<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Chat API routes
$route['api/chat/save-message'] = 'api/save_message';
$route['api/chat/save-session'] = 'api/save_session';
$route['api/chat/sessions'] = 'api/sessions';
$route['api/chat/session/(:any)'] = 'api/session/$1';
