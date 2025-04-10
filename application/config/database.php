<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => getenv('DB_HOST', 'localhost'),
	'username' => getenv('DB_USERNAME', 'root'),
	'password' => getenv('DB_PASSWORD', ''),
	'port' 	   => getenv('DB_PORT', '3306'),
	'database' => 'dbsatu_',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

//2025
$db['ref2025'] = array(
	'dsn'	=> '',
	'hostname' => getenv('DB_HOST', 'localhost'),
	'username' => getenv('DB_USERNAME', 'root'),
	'password' => getenv('DB_PASSWORD', ''),
	'port' 	   => getenv('DB_PORT', '3306'),
	'database' => 'dbref2025_',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['revisi2025'] = array(
	'dsn'	=> '',
	'hostname' => getenv('DB_HOST', 'localhost'),
	'username' => getenv('DB_USERNAME', 'root'),
	'password' => getenv('DB_PASSWORD', ''),
	'port' 	   => getenv('DB_PORT', '3306'),
	'database' => 'dbrkakl2025_revisi_',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['mytask_log'] = array(
	'dsn'	=> '',
	'hostname' => getenv('DB_HOST', 'localhost'),
	'username' => getenv('DB_USERNAME', 'root'),
	'password' => getenv('DB_PASSWORD', ''),
	'port' 	   => getenv('DB_PORT', '3306'),
	'database' => 't_mytask_log',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['mytask_link'] = array(
	'dsn'	=> '',
	'hostname' => getenv('DB_HOST', 'localhost'),
	'username' => getenv('DB_USERNAME', 'root'),
	'password' => getenv('DB_PASSWORD', ''),
	'port' 	   => getenv('DB_PORT', '3306'),
	'database' => 't_mytask_link_',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
