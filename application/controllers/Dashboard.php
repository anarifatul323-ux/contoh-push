<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        $this->load->model('Barang_model');
        $this->render('dashboard', array(
            'title'     => 'Dashboard',
            'stat'      => $this->Barang_model->statistik(),
            'menipis'   => $this->Barang_model->stok_menipis(5),
            'riwayat'   => $this->Barang_model->riwayat(array(), 8),
            'menunggu'  => $this->db->where('status', 'menunggu')->count_all_results('peminjaman'),
            'terlambat' => $this->db->where('status', 'disetujui')
                                    ->where('tanggal_kembali <', date('Y-m-d'))
                                    ->count_all_results('peminjaman'),
        ));
    }
}