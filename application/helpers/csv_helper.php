<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('parse_csv_string')) {
    function parse_csv_string($csvString, $delimiter = ',', $enclosure = '"')
    {
        $CI = &get_instance(); // Dapatkan instance CodeIgniter
        $CI->load->library('csvreader'); // Load library csvreader

        // Set options
        $CI->csvreader->separator = $delimiter;
        $CI->csvreader->enclosure = $enclosure;

        $parsedData = $CI->csvreader->parse_string($csvString); // Parsing

        return $parsedData;
    }
}
