<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_last_update')) {
    /**
     * Generate HTML for last update information with Indonesian timezone
     * 
     * @return string HTML content for last update display
     */
    function get_last_update() {
        date_default_timezone_set('Asia/Jakarta');
        $CI =& get_instance();
        $dateUpdate = $CI->fc->idtgl(date("Y/m/d"), 'hari');
        $timeUpdate = $CI->fc->idtgl(date("Y/m/d H:i:s"), 'jam');
        
        $html = '<div class="text-end">' . $dateUpdate . '</div>';
        $html .= '<div class="mt-1 small text-muted text-end">Update terakhir: <b>' . $timeUpdate . '</b> WIB</div>';
        
        return $html;
    }
}