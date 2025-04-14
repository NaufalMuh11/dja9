<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggaran extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('MyTask_model');
	}

	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q'];
		else show_404();

		if ($menu == 'MyTask') $this->mytask();
		else show_404();
	}


		#-- table top users --#
		private function mytask() {
            $year = $this->input->get('year') ?? date('Y');
            $month = $this->input->get('month') ?? date('n');
            
            // Add month name formatting
            $month_names = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            $data['month_name'] = $month_names[$month - 1];
            $data['year'] = $year;
            
            $this->load->model('MyTask_Model');
            $hourly_data = $this->MyTask_Model->get_hourly_users($year, $month);
            
            $data['hourly_users'] = json_encode($hourly_data);
            $data['top_users_by_month'] = json_encode($this->MyTask_model->get_top_users_by_month($year, $month));
            $data['current_year'] = $year;
            $data['current_month'] = $month;
            #-- card total users --#
            $data['total_users'] = json_encode($this->MyTask_model->get_total_users());
            #-- card active users --#
            $data['active_users'] = json_encode($this->MyTask_model->get_active_users());
            #-- card total modules --#
            $data['total_modules'] = json_encode($this->MyTask_model->get_total_modules());
            #-- card total services --#
            $data['total_services'] = json_encode($this->MyTask_model->get_total_services());
            
            $data['view'] = 'anggaran/index';
            $this->load->view('main/main', $data);
        }
}
