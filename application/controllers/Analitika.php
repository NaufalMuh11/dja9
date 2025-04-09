<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analitika extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q']; else show_404();

		if ($menu == '4n471k') $this->analitika();
		elseif ($menu == 'QR4DK') $this->QR_ADK();
		elseif ($menu == 'rangeThang') redirect(base_url('main/rangeThang'));
		else show_404();
	}

	private function QR_ADK() {
		$data['view'] = 'analitika/qr_adk';
		$this->load->view('main/main', $data);
	}

	private function analitika() {
		$data['view'] = 'analitika/index';
		$this->load->view('main/main', $data);
	}
	
}
