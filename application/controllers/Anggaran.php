<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('MyTask_model');
	}

	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q'];
		else show_404();

		if ($menu == 'MyTask') $this->mytask();
		else show_404();
	}

	private function mytask()
	{
		#-- table top users --#
		$year = $this->input->get('year') ? $this->input->get('year') : date('Y');
		$month = $this->input->get('month') ? $this->input->get('month') : date('m');
		$data['top_users_by_month'] = json_encode($this->MyTask_model->get_top_users_by_month($year, $month));
		$data['current_year'] = $year;
		$data['current_month'] = $month;
		#-- table total users --#
		$data['total_users'] = json_encode($this->MyTask_model->get_total_users());
		#-- table active users --#
		$data['active_users'] = json_encode($this->MyTask_model->get_active_users());
		#-- table total modules --#
		$data['total_modules'] = json_encode($this->MyTask_model->get_total_modules());
		#-- table total services --#
		$data['total_services'] = json_encode($this->MyTask_model->get_total_services());

		$data['view'] = 'anggaran/index';
		$this->load->view('main/main', $data);
	}
}
