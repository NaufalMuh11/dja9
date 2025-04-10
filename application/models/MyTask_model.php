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
}
