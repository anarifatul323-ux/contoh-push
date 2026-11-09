<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        if ($this->session->userdata('role') !== 'pelanggan') {
            redirect('dashboard');
        }
        $this->load->model(array('Barang_model', 'Master_model', 'Peminjaman_model'));
    }

    private function render($view, $data = array())
    {
        $this->load->view('pelanggan/header', $data);
        $this->load->view($view, $data);
        $this->load->view('pelanggan/footer');
    }

    // ===== Beranda: ringkasan + daftar barang yang bisa dipinjam =====
    public function index()
    {
        $f = array(
            'q'           => $this->input->get('q', TRUE),
            'kategori_id' => $this->input->get('kategori_id'),
        );
        $this->render('pelanggan/dashboard', array(
            'title'    => 'Beranda',
            'f'        => $f,
            'stat'     => $this->Peminjaman_model->stat($this->session->userdata('user_id')),
            'barang'   => $this->Barang_model->get_all($f),
            'kategori' => $this->Master_model->all('kategori'),
        ));
    }

    // ===== Form pengajuan peminjaman =====
    public function pinjam($id = 0)
    {
        $fv = $this->form_validation;
        $fv->set_rules('barang_id', 'Barang', 'required|callback__cek_barang',
            array('required' => '%s harus dipilih.'));
        $fv->set_rules('jumlah', 'Jumlah', 'required|integer|greater_than[0]|callback__cek_stok', array(
            'required'     => '%s wajib diisi.',
            'integer'      => '%s harus berupa angka bulat.',
            'greater_than' => '%s harus lebih dari nol.',
        ));
        $fv->set_rules('tanggal_pinjam', 'Tanggal pinjam', 'required|callback__tgl|callback__tidak_lampau');
        $fv->set_rules('tanggal_kembali', 'Tanggal kembali', 'required|callback__tgl|callback__tgl_kembali');
        $fv->set_rules('keperluan', 'Keperluan', 'required|trim|max_length[255]',
            array('required' => '%s wajib diisi.'));

        if ($fv->run()) {
            $this->Peminjaman_model->ajukan(array(
                'user_id'         => $this->session->userdata('user_id'),
                'barang_id'       => (int) $this->input->post('barang_id'),
                'jumlah'          => (int) $this->input->post('jumlah'),
                'tanggal_pinjam'  => $this->input->post('tanggal_pinjam'),
                'tanggal_kembali' => $this->input->post('tanggal_kembali'),
                'keperluan'       => $this->input->post('keperluan', TRUE),
            ));
            $this->session->set_flashdata('success', 'Pengajuan terkirim. Tunggu persetujuan admin.');
            redirect('pelanggan/riwayat');
        }

        $this->render('pelanggan/pinjam', array(
            'title'  => 'Pinjam Barang',
            'barang' => $this->Barang_model->get_all(),
            'pilih'  => (int) $id,
        ));
    }

    // ===== Riwayat peminjaman saya =====
    public function riwayat()
    {
        $this->render('pelanggan/riwayat', array(
            'title'  => 'Riwayat Saya',
            'daftar' => $this->Peminjaman_model->milik($this->session->userdata('user_id')),
        ));
    }

    public function batal($id = 0)
    {
        if ($this->input->method() !== 'post') {
            show_error('Metode tidak diizinkan.', 405);
        }
        if ($this->Peminjaman_model->batal($id, $this->session->userdata('user_id'))) {
            $this->session->set_flashdata('success', 'Pengajuan dibatalkan.');
        } else {
            $this->session->set_flashdata('error', 'Pengajuan tidak dapat dibatalkan.');
        }
        redirect('pelanggan/riwayat');
    }

    // ===== Callback validasi =====
    public function _cek_barang($str)
    {
        if (!$this->Barang_model->find((int) $str)) {
            $this->form_validation->set_message('_cek_barang', '%s tidak valid.');
            return FALSE;
        }
        return TRUE;
    }

    // Jumlah pinjam tidak boleh melebihi stok tersedia
    public function _cek_stok($jumlah)
    {
        $b = $this->Barang_model->find((int) $this->input->post('barang_id'));
        if ($b && (int) $jumlah > (int) $b->stok) {
            $this->form_validation->set_message('_cek_stok',
                'Jumlah melebihi stok. Stok "' . $b->nama_barang . '" tersedia ' . $b->stok . ' ' . $b->satuan . '.');
            return FALSE;
        }
        return TRUE;
    }

    public function _tgl($str)
    {
        $d = DateTime::createFromFormat('Y-m-d', $str);
        if (!($d && $d->format('Y-m-d') === $str)) {
            $this->form_validation->set_message('_tgl', '%s tidak valid.');
            return FALSE;
        }
        return TRUE;
    }

    public function _tidak_lampau($str)
    {
        if ($str < date('Y-m-d')) {
            $this->form_validation->set_message('_tidak_lampau', '%s tidak boleh sebelum hari ini.');
            return FALSE;
        }
        return TRUE;
    }

    public function _tgl_kembali($str)
    {
        $pinjam = $this->input->post('tanggal_pinjam');
        if ($pinjam && $str < $pinjam) {
            $this->form_validation->set_message('_tgl_kembali', 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.');
            return FALSE;
        }
        return TRUE;
    }
}