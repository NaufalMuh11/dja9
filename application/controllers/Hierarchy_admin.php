<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hierarchy_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tambahkan autentikasi admin di sini

        // Load model yang diperlukan
        $this->load->model('perbandingan_model');
    }

    public function create_table()
    {
        $thang = $this->input->get('thang');
        if (!$thang) {
            show_error('Year parameter required', 400);
        }

        $ref = $this->load->database('ref' . $thang, TRUE);

        $sql = "
        CREATE TABLE IF NOT EXISTS t_sbu_hierarchy (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kdsbu VARCHAR(10) NOT NULL,
            level INT NOT NULL COMMENT '1: title, 2: subtitle, 3: sub-subtitle',
            parent_kdsbu VARCHAR(10) NULL,
            nmsbu VARCHAR(255) NOT NULL,
            UNIQUE KEY (kdsbu),
            INDEX (parent_kdsbu),
            INDEX (level)
        )";

        $ref->query($sql);

        echo "Table created successfully";
    }

    public function migrate_data()
    {
        $thang = $this->input->get('thang');
        $kode = $this->input->get('kode');

        if (!$thang || !$kode) {
            show_error('Year and code parameters required', 400);
        }

        $ref = $this->load->database('ref' . $thang, TRUE);

        // Dapatkan data hirarkis dengan metode lama
        $this->load->model('perbandingan_model');
        $refMethod = new ReflectionMethod('Perbandingan_model', 'get_hierarchical_data_old');
        $refMethod->setAccessible(true);
        $hierarchy = $refMethod->invoke($this->perbandingan_model, $kode, $thang);

        if (empty($hierarchy)) {
            show_error('No data found for code: ' . $kode, 404);
        }

        // Kosongkan tabel hierarki untuk kode yang akan dimigrasi
        $ref->query("DELETE FROM t_sbu_hierarchy WHERE LEFT(kdsbu, 3) = ?", array($kode));

        // Masukkan data ke tabel hierarki
        $inserted = 0;
        foreach ($hierarchy as $key => $node) {
            $data = array(
                'kdsbu' => $node['kdsbu'],
                'level' => $node['level'],
                'nmsbu' => $node['nmsbu'],
                'parent_kdsbu' => isset($node['parent']) ? $node['parent'] : null
            );

            $ref->insert('t_sbu_hierarchy', $data);
            $inserted++;
        }

        echo "Migration completed. {$inserted} records inserted.";
    }

    public function check_hierarchy()
    {
        // Tampilkan hierarki untuk verifikasi
        $thang = $this->input->get('thang');
        $kode = $this->input->get('kode');

        if (!$thang || !$kode) {
            show_error('Year and code parameters required', 400);
        }

        $ref = $this->load->database('ref' . $thang, TRUE);

        $query = $ref->query("
            WITH RECURSIVE hierarchy AS (
                SELECT 
                    kdsbu,
                    nmsbu,
                    level,
                    parent_kdsbu,
                    0 as depth,
                    CAST(kdsbu AS CHAR(50)) as path
                FROM t_sbu_hierarchy
                WHERE level = 1 AND LEFT(kdsbu, 3) = ?
                
                UNION ALL
                
                SELECT 
                    c.kdsbu,
                    c.nmsbu,
                    c.level,
                    c.parent_kdsbu,
                    h.depth + 1,
                    CONCAT(h.path, ',', c.kdsbu) as path
                FROM t_sbu_hierarchy c
                JOIN hierarchy h ON c.parent_kdsbu = h.kdsbu
            )
            SELECT * FROM hierarchy
            ORDER BY path
        ", array($kode));

        $data['hierarchy'] = $query->result_array();
        echo json_encode($data);
    }
}
