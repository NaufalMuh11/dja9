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
     * Menyimpan informasi sesi percakapan (Generic, prefer save_or_update_session for user context)
     */
    public function save_session($data)
    {
        // Ensure user_id is part of $data if this is used directly
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
     * Mendapatkan detail sebuah sesi berdasarkan ID dan User ID.
     */
    public function get_session_details($session_id, $user_id)
    {
        $this->db->from('ragflow_sessions');
        $this->db->where('session_id', $session_id);
        $this->db->where('user_id', $user_id);
        return $this->db->get()->row();
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
     * Menyimpan pesan dalam percakapan (Generic, prefer save_individual_message, save_user_message, or save_assistant_message)
     */
    public function save_message($data)
    {
        // Ensure session_id in $data is validated against a user if used directly
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

    /**
     * Cek apakah session dengan session_id tertentu ada (generic check)
     */
    public function session_exists($session_id)
    {
        $this->db->select('session_id');
        $this->db->from('ragflow_sessions');
        $this->db->where('session_id', $session_id);
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->num_rows() > 0;
    }

    /**
     * Cek apakah session dengan session_id tertentu ada DAN milik user_id tertentu
     */
    public function session_exists_for_user($session_id, $user_id)
    {
        $this->db->select('session_id');
        $this->db->from('ragflow_sessions');
        $this->db->where('session_id', $session_id);
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->num_rows() > 0;
    }


    /**
     * Simpan atau update session chat untuk pengguna tertentu.
     */
    public function save_or_update_session($session_id, $user_id, $data)
    {
        if (empty($user_id)) {
            log_message('error', 'Attempted to save session without user_id. Session ID: ' . $session_id);
            return false;
        }

        // Cek apakah session sudah ada untuk user ini
        $this->db->where('session_id', $session_id);
        $this->db->where('user_id', $user_id);
        $existing = $this->db->get('ragflow_sessions')->row();

        // Selalu tambahkan timestamp update jika data diubah
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($existing) {
            // Update session yang ada
            $this->db->where('session_id', $session_id);
            $this->db->where('user_id', $user_id);
            $this->db->update('ragflow_sessions', $data);
            return $this->db->affected_rows() >= 0;
        } else {
            // Cek kolisi ID sesi (jika session_id sudah ada tapi milik user lain)
            $this->db->where('session_id', $session_id);
            if ($this->db->get('ragflow_sessions')->num_rows() > 0) {
                log_message('error', 'Session ID collision detected. Session ID: ' . $session_id);
                return false;
            }

            // Tambahkan data yang hanya ada saat insert
            $data['user_id'] = $user_id;
            $data['session_id'] = $session_id;
            $data['chat_id'] = 'sa_' . $user_id . '_' . uniqid();
            $data['created_at'] = date('Y-m-d H:i:s');

            // Insert sesi baru
            $this->db->insert('ragflow_sessions', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Ambil semua sessions dengan messages untuk endpoint sessions() untuk pengguna tertentu
     */
    public function get_user_sessions_with_messages($user_id)
    {
        $this->db->select('rs.*, rm.id as message_id, rm.role, rm.content, rm.reference, rm.created_at as message_timestamp');
        $this->db->from('ragflow_sessions rs');
        $this->db->join('ragflow_messages rm', 'rs.session_id = rm.session_id', 'left');
        $this->db->where('rs.user_id', $user_id);
        $this->db->where('rs.is_active', 1);
        $this->db->order_by('rs.updated_at', 'DESC');
        $this->db->order_by('rm.created_at', 'ASC');

        $query = $this->db->get();
        $results = $query->result_array();

        $sessions = [];
        foreach ($results as $row) {
            $session_id_key = $row['session_id'];

            if (!isset($sessions[$session_id_key])) {
                $sessions[$session_id_key] = [
                    'session_id' => $session_id_key,
                    'chat_id' => $row['chat_id'],
                    'user_id' => $row['user_id'],
                    'title' => $row['session_name'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'is_active' => $row['is_active'],
                    'messages' => []
                ];
            }

            if ($row['message_id']) {
                $citations = [];
                if (!empty($row['reference'])) {
                    $reference_data = json_decode($row['reference'], true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($reference_data['citations']) && is_array($reference_data['citations'])) {
                        $citations = $reference_data['citations'];
                    } else if (json_last_error() === JSON_ERROR_NONE && is_array($reference_data)) {
                        // If reference is a simple array, treat it as citations directly
                        $citations = $reference_data;
                    } else {
                        log_message('error', 'Invalid JSON format for reference: ' . json_last_error_msg());
                    }
                }

                $sessions[$session_id_key]['messages'][] = [
                    'id' => $row['message_id'],
                    'role' => $row['role'],
                    'content' => $row['content'],
                    'citations' => $citations,
                    // 'reference' => $row['reference'], // Optionally exclude raw reference if citations are parsed
                    'timestamp' => $row['message_timestamp']
                ];
            }
        }
        return array_values($sessions);
    }

    /**
     * Hapus session dan semua message terkait untuk pengguna tertentu
     */
    public function delete_user_session_and_messages($session_id, $user_id)
    {
        // Verifikasi kepemilikan sesi
        if (!$this->session_exists_for_user($session_id, $user_id)) {
            log_message('warning', "Attempt to delete session '$session_id' not belonging to user '$user_id' or session not found.");
            return false;
        }

        // Memulai transaksi
        $this->db->trans_start();

        // Hapus pesan terlebih dahulu
        $this->db->where('session_id', $session_id);
        $this->db->delete('ragflow_messages');

        // Hapus sesi, memastikan itu milik pengguna
        $this->db->where('session_id', $session_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('ragflow_sessions');

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', "Transaction failed for deleting session '$session_id' for user '$user_id'.");
            return false;
        } else {
            return true;
        }
    }

    /**
     * Simpan individual message ke database
     */
    public function save_individual_message($data)
    {
        $message_data = [
            'session_id' => $data['session_id'],
            'role' => $data['role'],
            'content' => $data['content'],
            'reference' => $data['reference'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('ragflow_messages', $message_data);
        return $this->db->insert_id();
    }

    /**
     * Simpan user message ke database
     */
    public function save_user_message($session_id, $content)
    {
        // session_id should be validated by controller to belong to current user
        $message_data = [
            'session_id' => $session_id,
            'role' => 'user',
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('ragflow_messages', $message_data);
        return $this->db->insert_id();
    }

    /**
     * Simpan assistant message ke database dengan citations
     */
    public function save_assistant_message($session_id, $content, $citations = [])
    {
        // session_id should be validated by controller to belong to current user
        $reference_data = null;
        if (!empty($citations)) {
            // Ensure citations are structured correctly before encoding
            $valid_citations = [];
            foreach ($citations as $citation) {
                if (isset($citation['title']) && isset($citation['text'])) {
                    $valid_citations[] = $citation;
                }
            }
            if (!empty($valid_citations)) {
                $reference_data = json_encode(['citations' => $valid_citations]);
            }
        }

        $message_data = [
            'session_id' => $session_id,
            'role' => 'assistant',
            'content' => $content,
            'reference' => $reference_data,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('ragflow_messages', $message_data);
        return $this->db->insert_id();
    }
}
