<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| RAGFlow API Configuration
|--------------------------------------------------------------------------
|
| Konfigurasi untuk koneksi ke API RAGFlow.
| - api_key: Kunci API RAGFlow
| - api_endpoint: URL endpoint API RAGFlow
| - default_assistant: ID asisten default yang digunakan
|
*/

// Load konfigurasi dari database jika tersedia
$CI =& get_instance();
$CI->load->database();

$query = $CI->db->get('ragflow_config');
$config_from_db = $query->row_array();

if ($config_from_db) {
    $config['ragflow_api_key'] = $config_from_db['api_key'];
    $config['ragflow_api_endpoint'] = $config_from_db['api_endpoint'];
    $config['ragflow_default_assistant'] = $config_from_db['default_assistant'];
} else {
    // Nilai default jika tidak ada di database
    $config['ragflow_api_key'] = 'ragflow-EwNDYwOTEyZjdmZjExZWY5NjA3MDI0Mm';
    $config['ragflow_api_endpoint'] = 'http://localhost:8080';
    $config['ragflow_default_assistant'] = 'e444a9aef7cc11ef905e0242ac1a0006'; // Ganti dengan ID asisten default
}