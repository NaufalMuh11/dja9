<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggaran extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q']; else show_404();
		
		if ($menu == 'MyTask') $this->mytask();
		else show_404();
	}
	
	private function mytask() {
		$data['view'] = 'anggaran/index';
		$this->load->view('main/main', $data);
	}
}
