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
        $this->dbsatu->select('
        t_mytask_log.iduser,
        t_user.nmuser,
        t_user.profilepic,
        t_user_group_satu.nmusergroup,
        COUNT(*) as click_count
    ');
        $this->dbsatu->from('t_mytask_log');
        $this->dbsatu->join('t_user', 't_mytask_log.iduser = t_user.iduser', 'left');
        $this->dbsatu->join('t_user_group_satu', 't_user.idusergroup = t_user_group_satu.idusergroup', 'left');

        if ($year !== 'all') {
            $this->dbsatu->where('YEAR(t_mytask_log.datetime)', $year);
        }

        if ($month !== 'all') {
            $this->dbsatu->where('MONTH(t_mytask_log.datetime)', $month);
        }

        $this->dbsatu->group_by('
        t_mytask_log.iduser,
        t_user.nmuser,
        t_user.profilepic,
        t_user_group_satu.nmusergroup
    ');

        $this->dbsatu->order_by('click_count', 'DESC');
        $this->dbsatu->limit(3);

        return $this->dbsatu->get()->result();
    }


    #-- Card --#
    public function get_total_users()
    {
        $this->dbsatu->select('COUNT(DISTINCT t_mytask_log.iduser) as total_users, t_user.nmuser, t_user.idusergroup, t_user_group_satu.nmusergroup');
        $this->dbsatu->from('t_mytask_log');
        $this->dbsatu->join('t_user', 't_mytask_log.iduser = t_user.iduser', 'left');
        $this->dbsatu->join('t_user_group_satu', 't_user.idusergroup = t_user_group_satu.idusergroup', 'left');
        $this->dbsatu->group_by('t_user.nmuser, t_user.idusergroup, t_user_group_satu.nmusergroup');
        $query = $this->dbsatu->get();

        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    public function get_active_users()
    {
        $this->dbsatu->select('t_mytask_log.iduser, t_user.nmuser, t_user.idusergroup, t_user_group_satu.nmusergroup, MAX(t_mytask_log.datetime) as last_activity');
        $this->dbsatu->from('t_mytask_log');
        $this->dbsatu->join('t_user', 't_mytask_log.iduser = t_user.iduser', 'left');
        $this->dbsatu->join('t_user_group_satu', 't_user.idusergroup = t_user_group_satu.idusergroup', 'left');
        $this->dbsatu->where('t_mytask_log.datetime >=', date('Y-m-d H:i:s', strtotime('-24 hours')));
        $this->dbsatu->group_by('t_mytask_log.iduser, t_user.nmuser, t_user.idusergroup, t_user_group_satu.nmusergroup');
        $query = $this->dbsatu->get();

        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    public function get_total_modules()
    {
        $this->dbsatu->select('t_mytask_link.buttonid, t_mytask_link.keterangan, t_mytask.namaproduk, COUNT(t_mytask_log.id) as usage_count');
        $this->dbsatu->from('t_mytask_link');
        $this->dbsatu->join('t_mytask', 't_mytask_link.produkid = t_mytask.idproduk', 'left');
        $this->dbsatu->join('t_mytask_log', 't_mytask_link.buttonid = t_mytask_log.buttonid', 'left');
        $this->dbsatu->group_by('t_mytask_link.buttonid, t_mytask_link.keterangan, t_mytask.namaproduk');
        $this->dbsatu->order_by('usage_count', 'DESC');
        $query = $this->dbsatu->get();

        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    public function get_total_services()
    {
        $this->dbsatu->select('t_mytask.idproduk, t_mytask.namaproduk, COUNT(DISTINCT t_mytask_link.buttonid) as module_count');
        $this->dbsatu->from('t_mytask');
        $this->dbsatu->join('t_mytask_link', 't_mytask.idproduk = t_mytask_link.produkid', 'left');
        $this->dbsatu->group_by('t_mytask.idproduk, t_mytask.namaproduk');
        $query = $this->dbsatu->get();

        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    // Dari branch views-mytask-modul
    public function get_top_module_distribution($year, $month) 
    {
        $this->db->select('t_mytask_link.keterangan, COUNT(t_mytask_log.id) as access_count');
        $this->db->from('t_mytask_log');
        $this->db->join('t_mytask_link', 't_mytask_log.buttonid = t_mytask_link.buttonid', 'left');
        $this->db->where('YEAR(t_mytask_log.datetime)', $year);
        $this->db->where('MONTH(t_mytask_log.datetime)', $month);
        $this->db->group_by('t_mytask_link.buttonid, t_mytask_link.keterangan');
        $this->db->order_by('access_count', 'DESC');
        $this->db->limit(5);
        return $this->db->get()->result();
    }

    public function get_daily_distribution_from_top_modules($year, $month, $top_modules)
    {
        if (empty($top_modules)) {
            return [];
        }

        $start_date = date("{$year}-{$month}-01");
        $end_date   = date("Y-m-t", strtotime($start_date));

        $this->db->select("DATE(l.datetime) AS Tanggal, link.keterangan AS Nama_Modul, COUNT(*) AS Jumlah_Aktivitas");
        $this->db->from("t_mytask_log l");
        $this->db->join("t_mytask_link link", "l.buttonid = link.buttonid", "inner");
        $this->db->where("l.datetime >=", $start_date);
        $this->db->where("l.datetime <=", $end_date);
        $this->db->where_in("link.keterangan", array_column($top_modules, 'keterangan'));
        $this->db->group_by("DATE(l.datetime), link.keterangan");
        $this->db->order_by("Tanggal, Nama_Modul");

        return $this->db->get()->result();
    }

    // Dari branch views-mytask
    public function get_hourly_users($year, $month) {
        $current_data = array();
        $previous_data = array();
        
        for ($hour = 0; $hour < 24; $hour++) {
            // Current year data
            $this->db->select('COUNT(DISTINCT iduser) as total');
            $this->db->where('YEAR(datetime)', $year);
            $this->db->where('MONTH(datetime)', $month);
            $this->db->where('HOUR(datetime)', $hour);
            $current_data[] = $this->db->get('t_mytask_log')->row()->total ?? 0;
            
            // Previous year data
            $this->db->select('COUNT(DISTINCT iduser) as total');
            $this->db->where('YEAR(datetime)', $year - 1);
            $this->db->where('MONTH(datetime)', $month);
            $this->db->where('HOUR(datetime)', $hour);
            $previous_data[] = $this->db->get('t_mytask_log')->row()->total ?? 0;
        }
        
        return array(
            'current_year' => $current_data,
            'previous_year' => $previous_data
        );
    }
}