<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GrafikSBMCompare extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('grafik_compare_model');
        $this->load->helper('url');
    }

    function index()
    {
        if (!$this->session->userdata('isLoggedIn')) redirect("login");

        $this->grafik_compare();
    }

    public function get_province_comparison_data_ajax()
    {
        $cost_type = $this->input->get('cost_type');
        $search_term = $this->input->get('search');

        $data = $this->grafik_compare_model->get_province_comparison_data($cost_type, $search_term);
        echo json_encode($data);
    }

    private function grafik_compare()
    {
        $data['title'] = 'Perbandingan SBM 2025 vs 2026 per Provinsi';
        $data['view'] = 'grafikSBM/compare';
        $data['categories'] = $this->grafik_compare_model->get_sbu_categories();
        $data['cost_types'] = $this->grafik_compare_model->get_cost_types();
        $this->load->view('main/main', $data);
    }
}
