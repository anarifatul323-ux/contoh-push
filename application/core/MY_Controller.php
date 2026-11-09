<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
              if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        if ($this->session->userdata('role') === 'pelanggan') {
            redirect('pelanggan');
        }
    }

    protected function render($view, $data = array())
    {
        $this->load->view('layout/header', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer');
    }

    protected function only_admin()
    {
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Halaman ini hanya untuk admin.');
            redirect('dashboard');
        }
    }

    protected function must_post()
    {
        if ($this->input->method() !== 'post') {
            show_error('Metode tidak diizinkan.', 405);
        }
    }

    public function _ada($str, $table)
    {
        if (!in_array($table, array('kategori', 'lokasi', 'kondisi', 'barang'), TRUE)) {
            return FALSE;
        }
        $ok = $this->db->where('id', (int) $str)->count_all_results($table) > 0;
        if (!$ok) {
            $this->form_validation->set_message('_ada', '%s tidak valid / tidak ditemukan.');
        }
        return $ok;
    }
    public function _tgl($str)
    {
        $d = DateTime::createFromFormat('Y-m-d', $str);
        $ok = $d && $d->format('Y-m-d') === $str;
        if (!$ok) {
            $this->form_validation->set_message('_tgl', '%s tidak valid.');
        }
        return $ok;
    }
}