<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perbandingan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Perbandingan_model');
    }

    function index()
    {
        if (! $this->session->userdata('isLoggedIn')) redirect("login");
        if (isset($_GET['q'])) $menu = $_GET['q'];
        else show_404();

        if ($menu == 'GrafSBM') $this->grafiksbm();
        else show_404();
    }

    private function grafiksbm()
    {
        $data['view'] = 'perbandingansbm/index';
        $this->load->view('main/main', $data);
    }

    public function get_boxplot_data($kode, $thang)
    {
        // if (!$this->session->userdata('isLoggedIn')) {
        //     $this->output->set_status_header(401);
        //     echo json_encode(['error' => 'Unauthorized']);
        //     return;
        // }

        $data = $this->Perbandingan_model->get_boxplot_data($kode, $thang);
        echo json_encode($data);
    }

    public function get_bar_data($kode, $thang)
    {
        // if (!$this->session->userdata('isLoggedIn')) {
        //     $this->output->set_status_header(401);
        //     echo json_encode(['error' => 'Unauthorized']);
        //     return;
        // }

        $data = $this->Perbandingan_model->get_bar_data($kode, $thang);
        echo json_encode($data);
    }
}
