<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends MY_Controller
{
    public function index()
    {
        $this->load->model(array('Barang_model'));
        $f = array(
            'barang_id' => $this->input->get('barang_id'),
            'jenis'     => $this->input->get('jenis', TRUE),
            'dari'      => $this->input->get('dari', TRUE),
            'sampai'    => $this->input->get('sampai', TRUE),
        );
        $this->render('riwayat/index', array(
            'title'   => 'Riwayat Inventaris',
            'f'       => $f,
            'barang'  => $this->Barang_model->get_all(),
            'riwayat' => $this->Barang_model->riwayat($f, 300),
        ));
    }
}