<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

// setting db connection s
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_port = '3306';

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $db_host,
	'username' => $db_username,
	'password' => $db_password,
	'port' 	   => $db_port,
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
	'hostname' => $db_host,
	'username' => $db_username,
	'password' => $db_password,
	'port' 	   => $db_port,
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
	'hostname' => $db_host,
	'username' => $db_username,
	'password' => $db_password,
	'port' 	   => $db_port,
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
	'hostname' => $db_host,
	'username' => $db_username,
	'password' => $db_password,
	'port' 	   => $db_port,
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
	'hostname' => $db_host,
	'username' => $db_username,
	'password' => $db_password,
	'port' 	   => $db_port,
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
