<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $ragflow_url;
    private $api_key;
    private $agent_id;
    private $curl_timeout = 120;
    private $curl_connect_timeout = 10;
    private $current_user_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ragflow_model');
        $this->current_user_id = $this->session->userdata('user_id');

        // Basic check for user_id, actual enforcement per method
        if (empty($this->current_user_id) && !$this->is_public_endpoint()) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }

        // Configuration
        $this->ragflow_url = getenv('RAGFLOW_URL');
        $this->api_key = getenv('RAGFLOW_API_KEY');
        $this->agent_id = getenv('RAGFLOW_AGENT_ID');

        // Validate required configuration
        if (empty($this->ragflow_url) || empty($this->api_key) || empty($this->agent_id)) {
            log_message('error', 'RAGFlow configuration missing: RAGFLOW_URL, API_KEY, or AGENT_ID not set');
        }
    }

    /**
     * Helper to determine if the current endpoint is public (e.g., health check)
     */
    private function is_public_endpoint()
    {
        $public_endpoints = ['health'];
        $current_method = $this->router->fetch_method();
        return in_array($current_method, $public_endpoints);
    }

    /**
     * Standardized JSON response helper
     */
    private function json_response($data, $status_code = 200)
    {
        $this->output->set_status_header($status_code);
        $this->output->set_content_type('application/json', 'utf-8');
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        $this->output->set_header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Handle OPTIONS request for CORS preflight
        if ($this->input->method() === 'options') {
            $this->output->set_output(null);
            return;
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_response = [
                'success' => false,
                'message' => 'JSON encoding error: ' . json_last_error_msg(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $json = json_encode($error_response);
        }
        $this->output->set_output($json);
    }

    /**
     * Enhanced text cleaning function
     */
    private function clean_response_text($text)
    {
        if (empty($text)) return '';
        $text = preg_replace('/##\d+\$\$/', '', $text);
        // $text = preg_replace('/\*\*([^*]+)\*\*/', '$1', $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);

        $text = trim($text);
        $encoding_fixes = ['â€œ' => '"', 'â€' => '"', 'â€™' => "'", 'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'â€"' => '–', 'â€"' => '—'];
        $text = str_replace(array_keys($encoding_fixes), array_values($encoding_fixes), $text);
        return $text;
    }

    /**
     * cURL helper
     */
    private function make_curl_request($url, $data = null, $headers = [], $method = null)
    {
        $ch = curl_init($url);

        $default_headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key,
            'User-Agent: RAGFlow-Client/1.0'
        ];
        $merged_headers = array_merge($default_headers, $headers);

        $curl_options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->curl_timeout,
            CURLOPT_CONNECTTIMEOUT => $this->curl_connect_timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $merged_headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3
        ];

        if (strtoupper($method) === 'POST') {
            $curl_options[CURLOPT_POST] = true;
            if ($data !== null) {
                $curl_options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
            }
        } elseif (strtoupper($method) === 'PUT') {
            $curl_options[CURLOPT_CUSTOMREQUEST] = "PUT";
            if ($data !== null) {
                $curl_options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
            }
        } elseif (strtoupper($method) === 'DELETE') {
            $curl_options[CURLOPT_CUSTOMREQUEST] = "DELETE";
            if ($data !== null) {
                $curl_options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
            }
        } elseif ($data !== null) { // Default to POST if data is present and method isn't specified as GET/DELETE etc.
            $curl_options[CURLOPT_POST] = true;
            $curl_options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
        }

        curl_setopt_array($ch, $curl_options);

        $response_body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error_num = curl_errno($ch);
        $curl_error_msg = curl_error($ch);

        curl_close($ch);

        if ($curl_error_num) {
            throw new Exception('Connection error: (' . $curl_error_num . ') ' . $curl_error_msg);
        }

        return [
            'response' => $response_body,
            'http_code' => $http_code
        ];
    }


    /**
     * Handle HTTP response codes
     */
    private function handle_http_response($http_code, $response_body = '')
    {
        switch ($http_code) {
            case 200:
            case 201:
            case 204:
                return true;
            case 400:
                $decoded_error = json_decode($response_body, true);
                $error_detail = isset($decoded_error['message']) ? $decoded_error['message'] : (isset($decoded_error['error']) ? $decoded_error['error'] : $response_body);
                throw new Exception('Bad Request. Please check your input. Detail: ' . $error_detail);
            case 401:
                throw new Exception('Authentication failed. Please check your API key.');
            case 403:
                throw new Exception('Access forbidden. Please check your permissions.');
            case 404:
                throw new Exception('Resource not found. Please check your agent_id or endpoint configuration.');
            case 429:
                throw new Exception('Rate limit exceeded. Please try again later.');
            case 500:
                throw new Exception('RAGFlow server error. Please try again later.');
            case 502:
            case 503:
            case 530:
                throw new Exception('RAGFlow service unavailable. The server might be down or under maintenance.');
            default:
                $error_msg = "RAGFlow API error (HTTP {$http_code})";
                if (!empty($response_body)) {
                    $error_msg .= ': ' . substr(strip_tags($response_body), 0, 200);
                }
                throw new Exception($error_msg);
        }
    }

    /**
     * Main chat endpoint with enhanced error handling
     */
    public function chat()
    {
        if (empty($this->current_user_id)) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }

        // Handle preflight requests
        if ($this->input->method() === 'options') {
            $this->json_response(['status' => 'OK']);
            return;
        }

        if ($this->input->method() !== 'post') {
            $this->json_response([
                'success' => false,
                'message' => 'Method not allowed. Use POST.'
            ], 405);
            return;
        }

        try {
            $json_input = file_get_contents('php://input');
            $input_data = json_decode($json_input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON input: ' . json_last_error_msg());
            }

            $user_message = $input_data['message'] ?? null;
            $session_id = $input_data['session_id'] ?? null;

            if (empty($user_message) || empty($session_id)) {
                $this->json_response(['success' => false, 'message' => 'Message and session_id are required.'], 400);
                return;
            }

            // Validate session exists in local DB and belongs to the user
            if (!$this->Ragflow_model->session_exists_for_user($session_id, $this->current_user_id)) {
                $this->json_response(['success' => false, 'message' => 'Session ID not found or access denied.'], 404);
                return;
            }

            // Dapatkan detail sesi dari database untuk memeriksa statusnya
            $session_details = $this->Ragflow_model->get_session_details($session_id, $this->current_user_id);

            // Jika sesi tidak ditemukan, kirim error.
            if (!$session_details) {
                $this->json_response(['success' => false, 'message' => 'Session ID not found or access denied.'], 404);
                return;
            }

            // Jika sesi BELUM AKTIF, ini adalah pesan pertama.
            if ($session_details->is_active == 0) {
                // Buat judul dari potongan pesan pertama
                $title = mb_substr($user_message, 0, 50);
                if (mb_strlen($user_message) > 50) {
                    $title .= '...';
                }

                // Siapkan data untuk mengaktifkan sesi
                $activation_data = [
                    'session_name' => $title,
                    'is_active' => 1
                ];

                // Update sesi di database
                $this->Ragflow_model->save_or_update_session(
                    $session_id,
                    $this->current_user_id,
                    $activation_data
                );
            }

            // Call RAGFlow for completion/chat
            $ragflow_chat_url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/completions";
            $ragflow_payload = [
                'question' => $user_message,
                'stream' => false,
                'session_id' => $session_id,
                'user_id' => $this->current_user_id
            ];

            $curl_result = $this->make_curl_request($ragflow_chat_url, $ragflow_payload, [], 'POST');
            $this->handle_http_response($curl_result['http_code'], $curl_result['response']);

            $ragflow_response_data = json_decode($curl_result['response'], true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($ragflow_response_data['data']['answer'])) {
                $error_detail = $ragflow_response_data['message'] ?? (isset($ragflow_response_data['error']) ? $ragflow_response_data['error'] : json_last_error_msg());
                throw new Exception('Invalid response from RAGFlow completion: ' . $error_detail);
            }

            $ai_answer = $this->clean_response_text($ragflow_response_data['data']['answer']);
            $citations = [];

            $this->json_response([
                'success' => true,
                'message' => $ai_answer,
                'citations' => $citations,
                'session_id' => $session_id,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            log_message('error', 'Chat API error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            $this->json_response(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new session with RAGFlow
     */
    private function create_ragflow_session()
    {
        try {
            if (empty($this->agent_id) || empty($this->api_key) || empty($this->ragflow_url)) {
                throw new Exception('Missing configuration: RAGFLOW_URL, RAGFLOW_AGENT_ID or RAGFLOW_API_KEY not set');
            }
            $this->test_server_connectivity();

            $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/sessions?user_id=" . $this->current_user_id;
            $post_data = ['name' => 'UserSession_' . $this->current_user_id . '_' . date('Y-m-d_H-i-s') . '_' . uniqid()];

            $curl_result = $this->make_curl_request($url, $post_data, [], 'POST');
            $this->handle_http_response($curl_result['http_code'], $curl_result['response']);

            $result = json_decode($curl_result['response'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode error while creating RAGFlow session: ' . json_last_error_msg());
            }
            if (!$result || !isset($result['data']['id'])) {
                $error_detail = $result['message'] ?? (isset($result['error']) ? $result['error'] : 'Unknown structure');
                throw new Exception('Invalid response structure from RAGFlow session creation: ' . $error_detail);
            }

            $welcome_message = '';
            if (isset($result['data']['message'][0]['content'])) {
                $welcome_message = $this->clean_response_text($result['data']['message'][0]['content']);
            } else if (isset($result['data']['opening_remarks'])) {
                $welcome_message = $this->clean_response_text($result['data']['opening_remarks']);
            }

            log_message('info', 'New RAGFlow session created: ' . $result['data']['id'] . ' for user_id: ' . $this->current_user_id);
            return [
                'success' => true,
                'message' => $welcome_message,
                'ragflow_session_id' => $result['data']['id']
            ];
        } catch (Exception $e) {
            log_message('error', 'RAGFlow session creation failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Save chat session to local database (called by JS if it creates a session concept first)
     * Or used by welcome to persist RAGFlow created session.
     */
    public function save_session()
    {
        if (empty($this->current_user_id)) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }
        if ($this->input->method() !== 'post') {
            $this->json_response(['success' => false, 'message' => 'Method not allowed. Use POST.'], 405);
            return;
        }
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON input for save_session: ' . json_last_error_msg());
            }
            if (empty($data['session_id'])) {
                $this->json_response(['success' => false, 'message' => 'Session ID is required to save session.'], 400);
                return;
            }

            // save_or_update_session will associate it with the current_user_id
            $result = $this->Ragflow_model->save_or_update_session(
                $data['session_id'],
                $this->current_user_id,
                [
                    'session_name' => $data['title'],
                    'is_active' => 0,
                ],
            );

            if ($result) {
                $this->json_response(['success' => true, 'message' => 'Session saved/updated successfully in local DB for user.']);
            } else {
                throw new Exception('Failed to save/update session in local DB for user.');
            }
        } catch (Exception $e) {
            log_message('error', 'Save session (local DB) error for user ' . $this->current_user_id . ': ' . $e->getMessage());
            $this->json_response(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Test server connectivity to RAGFlow URL
     */
    private function test_server_connectivity()
    {
        if (empty($this->ragflow_url)) {
            throw new Exception('RAGFlow URL is not configured.');
        }
        $ch = curl_init($this->ragflow_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_NOBODY => true
        ]);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Allow 2xx, 3xx, 4xx (except 401/403 if we want to be stricter, but for basic connectivity check this is fine)
        // Primarily concerned about 0 (host not found/timeout) or 5xx (server error on RAGFlow side)
        if ($http_code == 0 || ($http_code >= 500 && $http_code <= 599)) {
            throw new Exception('RAGFlow server (' . $this->ragflow_url . ') is not accessible. HTTP Code: ' . $http_code . '. Error: ' . $curl_error);
        }
    }

    /**
     * Get all chat sessions with their messages from local DB for the logged-in user
     */
    public function sessions()
    {
        if (empty($this->current_user_id)) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }
        if ($this->input->method() !== 'get') {
            $this->json_response(['success' => false, 'message' => 'Method not allowed. Use GET.'], 405);
            return;
        }
        try {
            $sessions = $this->Ragflow_model->get_user_sessions_with_messages($this->current_user_id);
            $this->json_response(['success' => true, 'sessions' => $sessions]);
        } catch (Exception $e) {
            log_message('error', 'Load sessions from local DB error for user ' . $this->current_user_id . ': ' . $e->getMessage());
            $this->json_response(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Welcome endpoint: creates RAGFlow session and saves it locally associated with the logged-in user.
     */
    public function welcome()
    {
        if (empty($this->current_user_id)) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }
        if ($this->input->method() !== 'post') {
            $this->json_response(['success' => false, 'message' => 'Method not allowed. Use POST.'], 405);
            return;
        }
        try {
            // 1. Buat sesi di layanan RAGFlow
            $ragflow_session_response = $this->create_ragflow_session();

            if (!$ragflow_session_response['success']) {
                throw new Exception('Failed to create session with RAGFlow service: ' . ($ragflow_session_response['error'] ?? 'Unknown error'));
            }

            $local_session_id = $ragflow_session_response['ragflow_session_id'];

            // 2. Siapkan data untuk sesi TIDAK AKTIF
            $inactive_session_data = [
                'session_name' => 'Sesi Baru (Belum Aktif)',
                'is_active' => 0
            ];

            // 3. Simpan sesi tidak aktif ini ke database lokal Anda
            $this->Ragflow_model->save_or_update_session(
                $local_session_id,
                $this->current_user_id,
                $inactive_session_data
            );

            // 4. Kirim respons ke frontend seperti biasa
            $this->json_response([
                'success' => true,
                'message' => $ragflow_session_response['message'],
                'session_id' => $local_session_id,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            log_message('error', 'Welcome endpoint error for user ' . $this->current_user_id . ': ' . $e->getMessage());
            $this->json_response(['success' => false, 'message' => 'Error creating session: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a session from local DB and attempt RAGFlow (via URL segment) for the logged-in user
     */
    public function session($session_id_from_url = null)
    {
        if (empty($this->current_user_id)) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }
        if ($this->input->method() !== 'delete') {
            $this->json_response(['success' => false, 'message' => 'Method not allowed for this endpoint. Use DELETE at /api/session/{session_id}.'], 405);
            return;
        }
        $this->_delete_session_logic($session_id_from_url);
    }


    /**
     * Internal logic for deleting a session for the logged-in user.
     */
    private function _delete_session_logic($session_id)
    {
        if (empty($session_id)) {
            $this->json_response(['success' => false, 'message' => 'Session ID is required for deletion.'], 400);
            return;
        }

        try {
            // Check if session exists for user before deletion
            $session_exists = $this->Ragflow_model->session_exists_for_user($session_id, $this->current_user_id);

            if (!$session_exists) {
                $this->json_response(['success' => false, 'message' => 'Session not found or does not belong to this user.'], 404);
                return;
            }

            // Delete from local database
            $local_delete_success = $this->Ragflow_model->delete_user_session_and_messages($session_id, $this->current_user_id);

            if (!$local_delete_success) {
                // Jika penghapusan lokal gagal, hentikan proses dan laporkan error.
                log_message('error', 'Local database deletion failed for session: ' . $session_id . ' for user: ' . $this->current_user_id);
                $this->json_response(['success' => false, 'message' => 'Failed to delete session from the primary database.'], 500);
                return;
            }

            // Attempt to delete from RAGFlow API
            try {
                if (!empty($this->agent_id) && !empty($this->ragflow_url)) {
                    $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/sessions";
                    $delete_data = [
                        'ids' => [$session_id]
                    ];
                    $curl_result = $this->make_curl_request($url, $delete_data, [], 'DELETE');

                    // Allow 404 from RAGFlow as it might have been already deleted or never existed there
                    if ($curl_result['http_code'] >= 400 && $curl_result['http_code'] !== 404) {
                        throw new Exception("RAGFlow API returned HTTP " . $curl_result['http_code']);
                    }
                    log_message('info', 'Attempted RAGFlow session deletion for: ' . $session_id . '. Status: ' . $curl_result['http_code']);
                }
            } catch (Exception $e) {
                log_message('warning', 'Could not delete session from RAGFlow service (but local deletion was successful): ' . $e->getMessage());
            }

            $this->json_response(['success' => true, 'message' => 'Session deleted successfully.']);
        } catch (Exception $e) {
            log_message('error', 'Unexpected error in delete session logic for user ' . $this->current_user_id . ': ' . $e->getMessage());
            $this->json_response(['success' => false, 'message' => 'An unexpected internal server error occurred.'], 500);
        }
    }


    /**
     * Save individual message to local database (user or assistant) for the logged-in user's session
     */
    public function save_message()
    {
        if (empty($this->current_user_id)) {
            $this->json_response(['success' => false, 'message' => 'Authentication required.'], 401);
            return;
        }
        if ($this->input->method() !== 'post') {
            $this->json_response(['success' => false, 'message' => 'Method not allowed. Use POST.'], 405);
            return;
        }
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON input for save_message: ' . json_last_error_msg());
            }

            if (empty($data['session_id']) || empty($data['role']) || !isset($data['content'])) {
                $this->json_response(['success' => false, 'message' => 'Missing required fields: session_id, role, content.'], 400);
                return;
            }
            if (!in_array($data['role'], ['user', 'assistant'])) {
                $this->json_response(['success' => false, 'message' => "Invalid role: '" . $data['role'] . "'. Must be 'user' or 'assistant'."], 400);
                return;
            }

            // Validate that the session_id belongs to the current user
            if (!$this->Ragflow_model->session_exists_for_user($data['session_id'], $this->current_user_id)) {
                $this->json_response(['success' => false, 'message' => 'Invalid session ID or session does not belong to user.'], 403);
                return;
            }

            $data['reference'] = null;

            // The model's save_individual_message doesn't need user_id directly, as session_id is already validated for the user.
            $message_id = $this->Ragflow_model->save_individual_message($data);

            if ($message_id) {
                $this->json_response(['success' => true, 'message' => 'Message saved to local DB for user.', 'message_id' => $message_id]);
            } else {
                throw new Exception('Failed to save message to local DB for user.');
            }
        } catch (Exception $e) {
            log_message('error', 'Save message (local DB) error for user ' . $this->current_user_id . ': ' . $e->getMessage());
            $this->json_response(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Health check endpoint for RAGFlow service connectivity
     */
    public function health()
    {
        // This is a public endpoint, no user_id check needed.
        if ($this->input->method() !== 'get') {
            $this->json_response(['success' => false, 'message' => 'Method not allowed. Use GET.'], 405);
            return;
        }
        try {
            $this->test_server_connectivity();
            $db_connected = $this->db->initialize();

            if (!$db_connected) {
                throw new Exception('Database connection failed.');
            }

            $this->json_response([
                'success' => true,
                'message' => 'Service is healthy. RAGFlow URL is accessible. Database connection is OK.',
                'ragflow_url_status' => 'Accessible',
                'database_status' => 'Connected',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            $this->json_response([
                'success' => false,
                'message' => 'Service unhealthy: ' . $e->getMessage(),
                'ragflow_url_status' => (strpos(strtolower($e->getMessage()), 'ragflow server') !== false || strpos(strtolower($e->getMessage()), 'ragflow url') !== false) ? 'Error' : 'Accessible (Error was DB or other)',
                'database_status' => (strpos(strtolower($e->getMessage()), 'database') !== false) ? 'Error' : 'OK (Error was RAGFlow or other)',
                'timestamp' => date('Y-m-d H:i:s')
            ], 503);
        }
    }

    // Fallback for OPTIONS requests if not handled by a specific route
    public function options_handler()
    {
        $this->json_response(null, 204);
    }
}
