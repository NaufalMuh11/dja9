<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Referensi_model extends CI_Model
{

    function get_rekap()
    {
        $ref = $this->load->database('ref2025', TRUE);
        $qry = $ref->query("Select left(kdsbu,3) kode, nmsbu uraian, '' ket From t_sbu Where Right(kdsbu,5)='00001' Order By 1");

        if ($qry && $qry->num_rows() > 0) {
            $raw_results = $qry->result_array();
            $sbm = $this->fc->ToArr($raw_results, 'kode');
            return $sbm;
        }

        return array(); // Return empty array if no results
    }

    function get_detil($kode)
    {
        try {
            $ref = $this->load->database('ref2025', TRUE);
            $query = $ref->query("SELECT kdsbu, nmsbu, satuan, biaya FROM t_sbu WHERE LEFT(kdsbu,3) = ? ORDER BY kdsbu", array($kode));

            if ($query && $query->num_rows() > 0) {
                return $query->result_array();
            }
            return array();
        } catch (Exception $e) {
            log_message('error', 'Error in get_detil: ' . $e->getMessage());
            return array();
        }
    }

}
