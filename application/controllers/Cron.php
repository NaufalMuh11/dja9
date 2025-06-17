<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // PENTING: Pastikan controller ini hanya bisa diakses dari Command Line (CLI)
        if (!$this->input->is_cli_request()) {
            log_message('error', 'Akses tidak sah ke Cron controller dari web.');
            show_error('Akses Ditolak. Controller ini hanya untuk CLI.', 403);
            exit;
        }

        $this->load->model('Ragflow_model');
    }

    /**
     * Metode untuk membersihkan sesi-sesi yang tidak aktif.
     * Jalankan dari CLI: php index.php cron cleanup_sessions
     */
    public function cleanup_sessions()
    {
        $rentang_waktu = '7 DAY';
        echo "Memulai pembersihan sesi tidak aktif yang lebih tua dari {$rentang_waktu}...\n";

        $deleted_rows = $this->Ragflow_model->delete_old_inactive_sessions($rentang_waktu);

        echo "Selesai. Jumlah sesi yang dihapus: " . $deleted_rows . "\n";
        log_message('info', 'Cron job cleanup_sessions: ' . $deleted_rows . ' baris telah dihapus.');
    }
}
