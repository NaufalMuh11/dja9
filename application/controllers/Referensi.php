<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Referensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Referensi_model');
    }

    public function index()
    {
        if (! $this->session->userdata('isLoggedIn')) {
            redirect("login");
        }
        if (isset($_GET['q'])) {
            $menu = $_GET['q'];
        } else {
            show_404();
        }

        // Get year from URL or session, default to 2025
        $tahun = $this->input->get('tahun') ?: $this->session->userdata('selected_year') ?: '2025';
        $this->session->set_userdata('selected_year', $tahun);

        if (isset($_GET['kode'])) {
            $kode = (int)$_GET['kode'];
        } else {
            $kode = '101';
        }

        if ($menu == 'RefSBM') {
            $this->rekap_sbm($tahun);
        } elseif ($menu == 'DtlSBM') {
            $this->detil_sbm($kode, $tahun);
        } else {
            show_404();
        }
    }

    private function rekap_sbm($tahun)
    {
        $data['tabel'] = $tahun === '2026' ?
            $this->Referensi_model->get_rekap2026() :
            $this->Referensi_model->get_rekap();
        $data['tahun_aktif'] = $tahun;
        $data['view'] = "referensi/v_sbm_rekap";
        $this->load->view('main/main', $data);
    }

    private function detil_sbm($kode, $tahun)
    {
        $data['tabel'] = $tahun === '2026' ?
            $this->Referensi_model->get_detil2026($kode) :
            $this->Referensi_model->get_detil($kode);
        $data['tahun_aktif'] = $tahun;
        $data['kode'] = $kode;
        $data['view']  = "referensi/v_sbm_detil";
        $this->load->view('main/main', $data);
    }
}
