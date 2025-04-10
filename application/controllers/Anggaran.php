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
	
	private function mytask() {
		$year = date('Y');
		$month = date('m');
		// Encode data ke JSON
		$data['top_users_by_month'] = json_encode($this->MyTask_model->get_top_users_by_month($year, $month));

		$data['view'] = 'anggaran/index';
		$this->load->view('main/main', $data);
	}
}
