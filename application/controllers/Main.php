<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    function index()
    { // default page
        if (! $this->session->userdata('isLoggedIn')) redirect("login");
        redirect(base_url('dashboard?q=d45h01'));
    }

    function rangeThang()
    {
        if (! $this->session->userdata('isLoggedIn')) redirect("login");
        $this->load->helper('range');
        $years = get_year_range("2023");
        echo json_encode($years);
    }
}
