<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyTask_model extends CI_Model
{
    private $dbsatu;

    public function __construct()
    {
        parent::__construct();
        $this->dbsatu = $this->load->database('dbsatu', TRUE);
    }

    public function get_top_users_by_month($year, $month)
    {
        $this->dbsatu->select('t_mytask_log.iduser, t_user.nmuser, t_user.profilepic, COUNT(*) as click_count');
        $this->dbsatu->from('t_mytask_log');
        $this->dbsatu->join('t_user', 't_mytask_log.iduser = t_user.iduser', 'left');

        if ($year !== 'all') {
            $this->dbsatu->where('YEAR(t_mytask_log.datetime)', $year);
        }

        if ($month !== 'all') {
            $this->dbsatu->where('MONTH(t_mytask_log.datetime)', $month);
        }

        $this->dbsatu->group_by('t_mytask_log.iduser, t_user.nmuser, t_user.profilepic');
        $this->dbsatu->order_by('click_count', 'DESC');
        $this->dbsatu->limit(3);
        return $this->dbsatu->get()->result();
    }

    #-- Card --#
    public function get_total_users()
    {
        $this->dbsatu->select('COUNT(DISTINCT iduser) as total');
        $query = $this->dbsatu->get('t_mytask_log');
        
        // Tambahkan pengecekan untuk menghindari error null
        if ($query && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return 0; // Return 0 jika tidak ada data
    }

    public function get_active_users()
    {
        $this->dbsatu->select('COUNT(DISTINCT iduser) as total');
        $this->dbsatu->where('datetime >=', date('Y-m-d H:i:s', strtotime('-24 hours')));
        $query = $this->dbsatu->get('t_mytask_log');
        
        if ($query && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return 0;
    }

    public function get_total_modules()
    {
        $this->dbsatu->select('COUNT(DISTINCT buttonid) as total');
        $query = $this->dbsatu->get('t_mytask_link');
        
        if ($query && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return 0;
    }

    public function get_total_services()
    {
        $this->dbsatu->select('COUNT(DISTINCT idproduk) as total');
        $query = $this->dbsatu->get('t_mytask');
        
        if ($query && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return 0;
    }
}
