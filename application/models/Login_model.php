<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function validate_login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password)); // Note: In production, use better hashing
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();
            return array(
                'id' => $user->id,
                'username' => $user->username,
                'nmuser' => $user->nmuser,
                'jabatan' => $user->jabatan,
                'profilepic' => $user->profilepic
            );
        }
        return false;
    }
}