<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $ragflow_url;
    private $api_key;
    private $agent_id;
    private $curl_timeout = 30;
    private $curl_connect_timeout = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Configuration
        $this->ragflow_url = getenv('RAGFLOW_URL');
        $this->api_key = getenv('RAGFLOW_API_KEY');
        $this->agent_id = getenv('RAGFLOW_AGENT_ID');

        // Validate required configuration
        if (empty($this->api_key) || empty($this->agent_id)) {
            log_message('error', 'RAGFlow configuration missing: API_KEY or AGENT_ID not set');
        }
    }

    /**
     * Standardized JSON response helper
     */
    private function json_response($data, $status_code = 200)
    {
        $this->output->set_status_header($status_code);
        $this->output->set_content_type('application/json', 'utf-8');

        // Add CORS headers for API access
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->output->set_header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        $this->output->set_header('Access-Control-Allow-Headers: Content-Type, Authorization');

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

        // Remove markdown-like patterns
        $text = preg_replace('/##\d+\$\$/', '', $text);
        $text = preg_replace('/\*\*([^*]+)\*\*/', '$1', $text); // Remove bold markers if needed for plain text

        // Clean up extra spaces and line breaks
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Fix common character encoding issues
        $encoding_fixes = [
            'â€œ' => '"',
            'â€' => '"',
            'â€™' => "'",
            'Ã¡' => 'á',
            'Ã©' => 'é',
            'Ã­' => 'í',
            'Ã³' => 'ó',
            'Ãº' => 'ú',
            'â€"' => '–',
            'â€"' => '—'
        ];

        $text = str_replace(array_keys($encoding_fixes), array_values($encoding_fixes), $text);

        return $text;
    }

    /**
     * Enhanced cURL helper with better error handling
     */
    private function make_curl_request($url, $data = null, $headers = [])
    {
        $ch = curl_init($url);

        $default_headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key,
            'User-Agent: RAGFlow-Client/1.0'
        ];

        $headers = array_merge($default_headers, $headers);

        $curl_options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->curl_timeout,
            CURLOPT_CONNECTTIMEOUT => $this->curl_connect_timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3
        ];

        if ($data !== null) {
            $curl_options[CURLOPT_POST] = true;
            $curl_options[CURLOPT_POSTFIELDS] = is_array($data) ? json_encode($data) : $data;
        }

        curl_setopt_array($ch, $curl_options);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        curl_close($ch);

        if ($curl_error) {
            throw new Exception('Connection error: ' . $curl_error);
        }

        return [
            'response' => $response,
            'http_code' => $http_code
        ];
    }

    /**
     * Handle HTTP response codes with detailed error messages
     */
    private function handle_http_response($http_code, $response = '')
    {
        switch ($http_code) {
            case 200:
            case 201:
                return true;
            case 401:
                throw new Exception('Authentication failed. Please check your API key.');
            case 403:
                throw new Exception('Access forbidden. Please check your permissions.');
            case 404:
                throw new Exception('Resource not found. Please check your agent_id configuration.');
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
                if (!empty($response)) {
                    $error_msg .= ': ' . substr($response, 0, 200);
                }
                throw new Exception($error_msg);
        }
    }

    /**
     * Main chat endpoint with enhanced error handling
     */
    public function chat()
    {
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
            // Input validation
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON input: ' . json_last_error_msg());
            }

            if (empty($data->message)) {
                $this->json_response([
                    'success' => false,
                    'message' => 'Invalid input: message field is required'
                ], 400);
                return;
            }

            // Sanitize message input
            $message = trim($data->message);
            if (strlen($message) > 4000) {
                throw new Exception('Message too long. Maximum 4000 characters allowed.');
            }

            // Session management
            $session_id = $this->get_or_create_session($data->session_id ?? null);

            // Call RAGFlow API
            $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/completions";

            $request_data = [
                'question' => $message,
                'stream' => false,
                'session_id' => $session_id
            ];

            $curl_result = $this->make_curl_request($url, $request_data);
            $this->handle_http_response($curl_result['http_code'], $curl_result['response']);

            $result = json_decode($curl_result['response'], true);

            if (!$result || !isset($result['data'])) {
                throw new Exception('Invalid response structure from RAGFlow API');
            }

            // Process response
            $formatted_response = $this->format_chat_response($result, $session_id);

            // Log successful interaction
            log_message('info', "Chat completion successful for session: {$session_id}");

            $this->json_response($formatted_response);
        } catch (Exception $e) {
            log_message('error', 'Chat API error: ' . $e->getMessage());
            $this->json_response([
                'success' => false,
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Get existing session or create new one
     */
    private function get_or_create_session($session_id = null)
    {
        if (!empty($session_id)) {
            // Validate existing session
            if ($this->validate_session($session_id)) {
                return $session_id;
            }
        }

        // Create new session
        $session_response = $this->create_session();
        if (!$session_response['success']) {
            throw new Exception('Failed to create session: ' . $session_response['error']);
        }

        return $session_response['session_id'];
    }

    /**
     * Validate if session exists and is active
     */
    private function validate_session($session_id)
    {
        try {
            $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/sessions/{$session_id}";
            $curl_result = $this->make_curl_request($url);

            return $curl_result['http_code'] === 200;
        } catch (Exception $e) {
            log_message('debug', 'Session validation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enhanced session creation with better error handling
     */
    private function create_session()
    {
        try {
            if (empty($this->agent_id) || empty($this->api_key)) {
                throw new Exception('Missing configuration: RAGFLOW_AGENT_ID or RAGFLOW_API_KEY not set');
            }

            // Test server connectivity
            $this->test_server_connectivity();

            $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/sessions";

            $post_data = [
                'name' => 'Session_' . date('Y-m-d_H-i-s') . '_' . uniqid()
            ];

            $curl_result = $this->make_curl_request($url, $post_data);
            $this->handle_http_response($curl_result['http_code'], $curl_result['response']);

            $result = json_decode($curl_result['response'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode error: ' . json_last_error_msg());
            }

            if (!$result || !isset($result['data']['id'])) {
                throw new Exception('Invalid response structure from session creation');
            }

            // Extract welcome message
            $welcome_message = '';
            if (isset($result['data']['message'][0]['content'])) {
                $welcome_message = $this->clean_response_text($result['data']['message'][0]['content']);
            }

            log_message('info', 'New session created: ' . $result['data']['id']);

            return [
                'success' => true,
                'message' => $welcome_message,
                'session_id' => $result['data']['id']
            ];
        } catch (Exception $e) {
            log_message('error', 'Session creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test server connectivity
     */
    private function test_server_connectivity()
    {
        $ch = curl_init($this->ragflow_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_NOBODY => true
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $http_code >= 500) {
            throw new Exception('RAGFlow server is not accessible. Server might be down.');
        }
    }

    /**
     * Format chat response with citations
     */
    private function format_chat_response($result, $session_id)
    {
        $cleaned_message = $this->clean_response_text($result['data']['answer'] ?? '');

        $formatted_response = [
            'success' => true,
            'message' => $cleaned_message,
            'citations' => [],
            'session_id' => $session_id,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Process citations
        if (isset($result['data']['reference']['chunks']) && is_array($result['data']['reference']['chunks'])) {
            foreach ($result['data']['reference']['chunks'] as $chunk) {
                $formatted_response['citations'][] = [
                    'title' => $chunk['document_name'] ?? 'Unknown Document',
                    'text' => $this->clean_response_text($chunk['content'] ?? 'No content'),
                    'source' => $chunk['source'] ?? null
                ];
            }
        }

        return $formatted_response;
    }

    /**
     * Welcome endpoint with enhanced session management
     */
    public function welcome()
    {
        try {
            $session_response = $this->create_session();

            if (!$session_response['success']) {
                throw new Exception('Failed to create session: ' . $session_response['error']);
            }

            $this->json_response([
                'success' => true,
                'message' => $session_response['message'],
                'session_id' => $session_response['session_id'],
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            log_message('error', 'Welcome endpoint error: ' . $e->getMessage());
            $this->json_response([
                'success' => false,
                'message' => 'Error creating session: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Get chat history for a session
     */
    public function history($session_id = null)
    {
        if ($session_id === null) {
            $session_id = $this->input->get('session_id');
        }

        if (empty($session_id)) {
            $this->json_response([
                'success' => false,
                'message' => 'Session ID is required'
            ], 400);
            return;
        }

        try {
            $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/sessions/{$session_id}/messages";

            $curl_result = $this->make_curl_request($url);
            $this->handle_http_response($curl_result['http_code'], $curl_result['response']);

            $result = json_decode($curl_result['response'], true);

            if (!$result) {
                throw new Exception('Invalid response from history API');
            }

            $this->json_response([
                'success' => true,
                'data' => $result['data'] ?? [],
                'session_id' => $session_id,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            log_message('error', 'History API error: ' . $e->getMessage());
            $this->json_response([
                'success' => false,
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Delete a session
     */
    public function delete_session()
    {
        if ($this->input->method() !== 'delete' && $this->input->method() !== 'post') {
            $this->json_response([
                'success' => false,
                'message' => 'Method not allowed'
            ], 405);
            return;
        }

        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if (empty($data->session_id)) {
                $this->json_response([
                    'success' => false,
                    'message' => 'Session ID is required'
                ], 400);
                return;
            }

            $url = $this->ragflow_url . "/api/v1/agents/{$this->agent_id}/sessions/{$data->session_id}";

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->curl_timeout,
                CURLOPT_CONNECTTIMEOUT => $this->curl_connect_timeout,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->api_key
                ]
            ]);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $this->handle_http_response($http_code, $response);

            $this->json_response([
                'success' => true,
                'message' => 'Session deleted successfully',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            log_message('error', 'Delete session error: ' . $e->getMessage());
            $this->json_response([
                'success' => false,
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ], 500);
        }
    }

    /**
     * Health check endpoint
     */
    public function health()
    {
        try {
            $this->test_server_connectivity();

            $this->json_response([
                'success' => true,
                'message' => 'Service is healthy',
                'ragflow_url' => $this->ragflow_url,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            $this->json_response([
                'success' => false,
                'message' => 'Service unhealthy: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ], 503);
        }
    }
}
