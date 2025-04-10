<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggaran extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('MyTask_model');
	}

	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q']; else show_404();
		
		if ($menu == 'MyTask') $this->mytask();
		else show_404();
	}

	private function mytask()
	{		
		$year = $this->input->get('year') ? $this->input->get('year') : date('Y');
		$month = $this->input->get('month') ? $this->input->get('month') : date('m');

		$data['top_users_by_month'] = json_encode($this->MyTask_model->get_top_users_by_month($year, $month));
		$data['current_year'] = $year;
		$data['current_month'] = $month;
		$data['module_distribution'] = json_encode($this->MyTask_model->get_top_module_distribution($year, $month));

		$data['view'] = 'anggaran/index';
		$this->load->view('main/main', $data);
	}

	public function get_module_distribution()
	{
        $year = $this->input->get('year');
        $month = $this->input->get('month');
        
        $data = $this->MyTask_model->get_top_module_distribution($year, $month);
        
        header('Content-Type: application/json');
        echo json_encode($data);
	}
}
