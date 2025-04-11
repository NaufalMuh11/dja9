<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grafik_compare_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_province_comparison_data($cost_type = null, $search_term = null)
    {
        // Get data from 2025 database
        $db2025 = $this->load->database('ref2025', TRUE);
        $db2025->select('kdsbu, nmsbu, biaya, satuan');
        $db2025->from('t_sbu');

        // Filter by cost type
        if ($cost_type && $cost_type != 'all') {
            $db2025->like('nmsbu', $cost_type);
        }

        // Filter by province name
        if ($search_term) {
            $db2025->like('nmsbu', $search_term);
        }

        $query2025 = $db2025->get();
        $data2025 = $query2025->result();

        // Get data from 2026 database
        $db2026 = $this->load->database('ref2026', TRUE);
        $db2026->select('kdsbu, nmsbu, biaya12 as biaya, satuan');
        $db2026->from('t_sbu');

        // Filter by cost type
        if ($cost_type && $cost_type != 'all') {
            $db2026->like('nmsbu', $cost_type);
        }

        // Filter by province name
        if ($search_term) {
            $db2026->like('nmsbu', $search_term);
        }

        $query2026 = $db2026->get();
        $data2026 = $query2026->result();

        // Extract province names from item names (assumes province is mentioned in the item name)
        $provinces = $this->get_provinces_from_data(array_merge($data2025, $data2026));

        // Organize data by province
        $province_data = [];

        foreach ($provinces as $province) {
            $province_costs = [
                'province' => $province,
                'biaya_2025' => 0,
                'biaya_2026' => 0,
                'difference' => 0,
                'percentage_change' => 0
            ];

            // Find 2025 costs for this province
            foreach ($data2025 as $item) {
                if (stripos($item->nmsbu, $province) !== false) {
                    $province_costs['biaya_2025'] += floatval($item->biaya);
                }
            }

            // Find 2026 costs for this province
            foreach ($data2026 as $item) {
                if (stripos($item->nmsbu, $province) !== false) {
                    $province_costs['biaya_2026'] += floatval($item->biaya);
                }
            }

            // Calculate difference and percentage change
            $province_costs['difference'] = $province_costs['biaya_2026'] - $province_costs['biaya_2025'];

            if ($province_costs['biaya_2025'] > 0) {
                $province_costs['percentage_change'] = ($province_costs['difference'] / $province_costs['biaya_2025']) * 100;
            } else {
                $province_costs['percentage_change'] = ($province_costs['biaya_2026'] > 0) ? 100 : 0;
            }

            $province_data[] = $province_costs;
        }

        return $province_data;
    }

    // Helper function to extract province names from data
    private function get_provinces_from_data($data)
    {
        $provinces = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Jambi',
            'Sumatera Selatan',
            'Bengkulu',
            'Lampung',
            'Bangka Belitung',
            'Kepulauan Riau',
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'DI Yogyakarta',
            'Jawa Timur',
            'Banten',
            'Bali',
            'Nusa Tenggara Barat',
            'Nusa Tenggara Timur',
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat',
            'Maluku',
            'Maluku Utara',
            'Papua',
            'Papua Barat',
            'Papua Selatan',
            'Papua Tengah',
            'Papua Pegunungan'
        ];

        $found_provinces = [];

        foreach ($data as $item) {
            foreach ($provinces as $province) {
                if (stripos($item->nmsbu, $province) !== false) {
                    $found_provinces[$province] = true;
                    break;
                }
            }
        }

        return array_keys($found_provinces);
    }

    public function get_cost_types()
    {
        // Define common cost types
        $cost_types = [
            ['code' => 'kendaraan', 'name' => 'Satuan Biaya Kendaraan'],
            ['code' => 'makan', 'name' => 'Satuan Biaya Makan'],
            ['code' => 'penginapan', 'name' => 'Satuan Biaya Penginapan'],
            ['code' => 'transportasi', 'name' => 'Satuan Biaya Transportasi'],
            ['code' => 'perjalanan dinas', 'name' => 'Satuan Biaya Perjalanan Dinas']
        ];

        return $cost_types;
    }
}
