<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referensi extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Referensi_model');
    }

    function index() {
        if (! $this->session->userdata('isLoggedIn')) redirect("login");
        if (isset($_GET['q'])) $menu = $_GET['q']; else show_404();
        if (isset($_GET['kode']))       $kode = (int)$_GET['kode']; else $kode = '101';

        if      ($menu == 'RefSBM') $this->rekap_sbm();
        else if ($menu == 'DtlSBM') $this->detil_sbm($kode);
        else show_404();
    }


    private function rekap_sbm()
    {
        $data['tabel'] = $this->Referensi_model->get_rekap();
        // Debug
        // echo '<pre>';
        // print_r($data['tabel']);
        // echo '</pre>';
        // exit;

        $data['view'] = "referensi/v_sbm_rekap";
        $this->load->view('main/main', $data);
    }

    private function detil_sbm($kode)
    {
        $data['tabel'] = $this->Referensi_model->get_detil($kode);
        $data['view']  = "referensi/v_sbm_detil";
        $this->load->view('main/main', $data);
    }
}
