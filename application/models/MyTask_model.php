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
}
