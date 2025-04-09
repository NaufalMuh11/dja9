<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->session->userdata('isLoggedIn')) {
            redirect(base_url('main?q=54t0e'));
        }
        $this->load->view('login/login_view');
    }

    public function process()
    {
        echo "masuk";

        // Check if already logged in
        if ($this->session->userdata('isLoggedIn')) {
            redirect(base_url('main?q=54t0e'));
            return;
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->login_model->validate_login($username, $password);

        if ($user) {
            // Set session data
            $this->session->set_userdata(array(
                'thang' => date('Y'),
                'isLoggedIn' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'nmuser' => $user['nmuser'],
                'jabatan' => $user['jabatan'],
                'profilepic' => $user['profilepic']
            ));

            // Set flash message for success
            $this->session->set_flashdata('success', 'Login successful!');
            redirect(base_url('main?q=54t0e'));
        } else {
            // Set flash message for error
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
