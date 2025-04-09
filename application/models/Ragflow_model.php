<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ragflow_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Menyimpan informasi sesi percakapan
     */
    public function save_session($data)
    {
        $this->db->insert('ragflow_sessions', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Mendapatkan sesi aktif untuk pengguna tertentu
     */
    public function get_active_session($user_id)
    {
        $this->db->select('*');
        $this->db->from('ragflow_sessions');
        $this->db->where('user_id', $user_id);
        $this->db->where('is_active', 1);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Nonaktifkan semua sesi untuk pengguna tertentu
     */
    public function deactivate_all_sessions($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('ragflow_sessions', ['is_active' => 0]);
        return $this->db->affected_rows();
    }
    
    /**
     * Mendapatkan daftar sesi percakapan untuk pengguna tertentu
     */
    public function get_user_sessions($user_id)
    {
        $this->db->select('ragflow_sessions.*, COUNT(ragflow_messages.id) as message_count');
        $this->db->from('ragflow_sessions');
        $this->db->join('ragflow_messages', 'ragflow_sessions.session_id = ragflow_messages.session_id', 'left');
        $this->db->where('ragflow_sessions.user_id', $user_id);
        $this->db->group_by('ragflow_sessions.session_id');
        $this->db->order_by('ragflow_sessions.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Menyimpan pesan dalam percakapan
     */
    public function save_message($data)
    {
        $this->db->insert('ragflow_messages', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Mendapatkan semua pesan dalam sesi percakapan
     */
    public function get_session_messages($session_id)
    {
        $this->db->select('*');
        $this->db->from('ragflow_messages');
        $this->db->where('session_id', $session_id);
        $this->db->order_by('created_at', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Mendapatkan pesan terakhir dari sesi percakapan
     */
    public function get_last_message($session_id)
    {
        $this->db->select('*');
        $this->db->from('ragflow_messages');
        $this->db->where('session_id', $session_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        return $query->row_array();
    }
}