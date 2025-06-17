<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q'];
		else show_404();

		if ($menu == 'd45h01') $this->monitoring_blokir();
		elseif ($menu == 'd45h02') $this->dashboard_magang();
		elseif ($menu == 'rangeThang') echo $this->rangeThang();
		else show_404();
	}

	private function monitoring_blokir()
	{
		$data['view'] = 'dashboard/blokir_monitoring';
		$this->load->view('main/main', $data);
	}

	private function dashboard_magang()
	{
		$data['view'] = 'dashboard/team';
		$this->load->view('main/main', $data);
	}

	private function rangeThang()
	{
		$this->load->helper('range');
		$years = get_year_range("2024");
		echo json_encode($years);
	}
}
