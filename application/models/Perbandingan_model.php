<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Perbandingan_model extends CI_Model
{
    function test() {
        $query = $this->db->query("SELECT * FROM t_sbu");
        return $query->result();
    }
}