<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CsvParser extends CI_Controller
{

    public function parse_csv()
    {
        $this->input->raw_input_stream;
        $requestData = json_decode($this->input->raw_input_stream, true);

        if ($requestData && isset($requestData['csvData']) && isset($requestData['config'])) {
            $csvData = $requestData['csvData'];
            $config = $requestData['config'];

            // Load helper
            $this->load->helper('csv');

            // Panggil fungsi helper
            try {
                $parsedData = parse_csv_string($csvData, $config['delimiter'], $config['quoteChar']);
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($parsedData));
            } catch (Exception $e) {
                $this->output
                    ->set_status_header(500)
                    ->set_output(json_encode(['error' => $e->getMessage()]));
            }
        } else {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Invalid request data']));
        }
    }
}
