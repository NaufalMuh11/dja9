<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggaran extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('MyTask_model');
	}

	function index()
	{
		if (! $this->session->userdata('isLoggedIn')) redirect("login");
		if (isset($_GET['q'])) $menu = $_GET['q']; else show_404();
		
		if ($menu == 'MyTask') $this->mytask();
		else show_404();
	}

	private function mytask()
{		
	$year = $this->input->get('year') ? $this->input->get('year') : date('Y');
	$month = $this->input->get('month') ? $this->input->get('month') : date('m');

	$top_modules = $this->MyTask_model->get_top_module_distribution($year, $month);
	$daily_data = $this->MyTask_model->get_daily_distribution_from_top_modules($year, $month, $top_modules);

	// Prepare data for line chart
	$series = [];
	$dates = [];
	
	// Group data by module
	foreach ($top_modules as $module) {
	    $moduleData = [
	        'name' => $module->keterangan,
	        'data' => array_fill(0, 31, 0) // Initialize with zeros
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

	$data['module_activity_data'] = json_encode($series);
	$data['top_users_by_month'] = json_encode($this->MyTask_model->get_top_users_by_month($year, $month));
	$data['current_year'] = $year;
	$data['current_month'] = $month;
	$data['module_distribution'] = json_encode($top_modules);

	// gunakan ulang data top_modules untuk distribusi per hari
	$data['module_daily_distribution'] = json_encode(
		$this->MyTask_model->get_daily_distribution_from_top_modules($year, $month, $top_modules)
	);

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
