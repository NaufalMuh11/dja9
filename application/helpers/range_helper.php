<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_year_range')) {
    /**
     * Generate a range of years from init year to next year
     * 
     * @param string $initYear The initial year to start from
     * @param bool $descending Whether to sort years in descending order
     * @return array Array of years
     */
    function get_year_range($initYear = null, $descending = true) {
        $nextYear = date("Y", strtotime("+1 year"));
        $initYear = $initYear ?? $nextYear;
        
        $initYearInt = (int)$initYear;
        $nextYearInt = (int)$nextYear;
        
        if ($descending) {
            return range($nextYearInt, $initYearInt, -1);
        } else {
            return range($initYearInt, $nextYearInt);
        }
    }
}