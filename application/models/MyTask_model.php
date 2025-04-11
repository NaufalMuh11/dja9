<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyTask_model extends CI_Model
{
	private $db;

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('dbsatu', TRUE);
	}

	public function get_top_users_by_month($year, $month)
	{
		$this->db->select('t_mytask_log.iduser, t_user.nmuser, t_user.profilepic, COUNT(*) as click_count');
		$this->db->from('t_mytask_log');
		$this->db->join('t_user', 't_mytask_log.iduser = t_user.iduser', 'left');

		if ($year !== 'all') {
			$this->db->where('YEAR(t_mytask_log.datetime)', $year);
		}

		if ($month !== 'all') {
			$this->db->where('MONTH(t_mytask_log.datetime)', $month);
		}

		$this->db->group_by('t_mytask_log.iduser, t_user.nmuser, t_user.profilepic');
		$this->db->order_by('click_count', 'DESC');
		$this->db->limit(3);
		return $this->db->get()->result();
	}

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


}
