<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perbandingan extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
        if (! $this->session->userdata('isLoggedIn')) redirect("login");
        if (isset($_GET['q'])) $menu = $_GET['q']; else show_404();

        if ($menu == 'GrafSBM') $this->grafiksbm();
        else show_404();
    }

    private function grafiksbm() {
		$data['view'] = 'perbandingansbm/index';
		$this->load->view('main/main', $data);
	}
}