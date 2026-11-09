<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Barang_model', 'Transaksi_model'));
    }

    public function masuk()
    {
        $this->proses('masuk');
    }

    public function keluar()
    {
        $this->proses('keluar');
    }

    private function proses($jenis)
    {
        $fv = $this->form_validation;
        $fv->set_rules('barang_id', 'Barang', 'required|callback__ada[barang]',
            array('required' => '%s harus dipilih.'));

        $rule = 'required|integer|greater_than[0]';
        if ($jenis === 'keluar') {
            $rule .= '|callback__cek_stok';
        }
        $fv->set_rules('jumlah', 'Jumlah', $rule, array(
            'required'     => '%s wajib diisi.',
            'integer'      => '%s harus berupa angka bulat.',
            'greater_than' => '%s harus lebih dari nol.',
        ));
        $fv->set_rules('tanggal', 'Tanggal', 'required|callback__tgl');
        $fv->set_rules('keterangan', 'Keterangan', 'trim|max_length[255]');

        if ($fv->run()) {
            $res = $this->Transaksi_model->$jenis(array(
                'barang_id'  => (int) $this->input->post('barang_id'),
                'jumlah'     => (int) $this->input->post('jumlah'),
                'tanggal'    => $this->input->post('tanggal'),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'user_id'    => $this->session->userdata('user_id'),
            ));
            $this->session->set_flashdata($res[0] ? 'success' : 'error', $res[1]);
            redirect('transaksi/' . $jenis);
        }

        $this->render('transaksi/form', array(
            'title'  => 'Barang ' . ucfirst($jenis),
            'jenis'  => $jenis,
            'barang' => $this->Barang_model->get_all(),
            'daftar' => $this->Transaksi_model->daftar($jenis, 15),
        ));
    }

    // Barang keluar tidak boleh melebihi stok
    public function _cek_stok($jumlah)
    {
        $b = $this->Barang_model->find((int) $this->input->post('barang_id'));
        if ($b && (int) $jumlah > (int) $b->stok) {
            $this->form_validation->set_message('_cek_stok',
                'Stok tidak mencukupi. Stok "' . $b->nama_barang . '" tersedia ' . $b->stok . ' ' . $b->satuan .
                ', sedangkan jumlah keluar yang diminta ' . (int) $jumlah . '.');
            return FALSE;
        }
        return TRUE;
    }
}