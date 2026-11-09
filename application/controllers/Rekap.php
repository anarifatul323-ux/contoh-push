<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends MY_Controller
{
    public function index()
    {
        $this->load->model('Barang_model');
        $this->render('rekap/index', array(
            'title'        => 'Rekap Inventaris',
            'per_kategori' => $this->Barang_model->rekap('kategori'),
            'per_lokasi'   => $this->Barang_model->rekap('lokasi'),
            'per_kondisi'  => $this->Barang_model->rekap('kondisi'),
            'barang'       => $this->Barang_model->rekap_barang(),
            'stat'         => $this->Barang_model->statistik(),
        ));
    }
}