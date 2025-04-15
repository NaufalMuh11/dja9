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

	private function mytask()
	{		
		$year = $this->input->get('year') ? $this->input->get('year') : date('Y');
		$month = $this->input->get('month') ? $this->input->get('month') : date('m');

		// Add month name formatting
		$month_names = [
			'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
			'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];
		$data['month_name'] = $month_names[$month - 1];
		$data['year'] = $year;

		// Modul data dari views-mytask-modul
		$top_modules = $this->MyTask_model->get_top_module_distribution($year, $month);
		$daily_data = $this->MyTask_model->get_daily_distribution_from_top_modules($year, $month, $top_modules);

		// Prepare data for line chart
		$series = [];
		
		// Group data by module
		foreach ($top_modules as $module) {
			$moduleData = [
				'name' => $module->keterangan,
				'data' => array_fill(0, 31, 0) 
			];
			$series[] = $moduleData;
		}

		// Fill in actual values
		foreach ($daily_data as $record) {
			$day = (int)date('d', strtotime($record->Tanggal)) - 1;
			$moduleIndex = array_search($record->Nama_Modul, array_column($series, 'name'));
			if ($moduleIndex !== false) {
				$series[$moduleIndex]['data'][$day] = (int)$record->Jumlah_Aktivitas;
			}
		}

		// Data dari views-mytask-modul
		$data['module_activity_data'] = json_encode($series);
		$data['module_distribution'] = json_encode($top_modules);
		$data['module_daily_distribution'] = json_encode(
			$this->MyTask_model->get_daily_distribution_from_top_modules($year, $month, $top_modules)
		);

		// Data dari views-mytask (hourly data dan cards)
		$hourly_data = $this->MyTask_model->get_hourly_users($year, $month);
		$data['hourly_users'] = json_encode($hourly_data);
		$data['top_users_by_month'] = json_encode($this->MyTask_model->get_top_users_by_month($year, $month));
		$data['current_year'] = $year;
		$data['current_month'] = $month;
		
		// Cards dari views-mytask
		$data['total_users'] = json_encode($this->MyTask_model->get_total_users());
		$data['active_users'] = json_encode($this->MyTask_model->get_active_users());
		$data['total_modules'] = json_encode($this->MyTask_model->get_total_modules());
		$data['total_services'] = json_encode($this->MyTask_model->get_total_services());

		$data['view'] = 'anggaran/index';
		$this->load->view('main/main', $data);
	}

	public function get_module_distribution()
	{
		$year = $this->input->get('year') ?? date('Y');
		$month = $this->input->get('month') ?? date('m');
		
		$top_modules = $this->MyTask_model->get_top_module_distribution($year, $month);
		$daily_distribution = $this->MyTask_model->get_daily_distribution_from_top_modules($year, $month, $top_modules);
		
		$response = [
			'module_distribution' => $top_modules,
			'daily_distribution' => $daily_distribution
		];
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}