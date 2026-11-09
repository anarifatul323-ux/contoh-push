<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Barang_model', 'Master_model'));
    }

    private function ref(&$data)
    {
        $data['kategori'] = $this->Master_model->all('kategori');
        $data['lokasi']   = $this->Master_model->all('lokasi');
        $data['kondisi']  = $this->Master_model->all('kondisi');
    }

    // ===== Daftar + pencarian + penyaringan =====
    public function index()
    {
        $f = array(
            'q'           => $this->input->get('q', TRUE),
            'kategori_id' => $this->input->get('kategori_id'),
            'lokasi_id'   => $this->input->get('lokasi_id'),
            'kondisi_id'  => $this->input->get('kondisi_id'),
        );
        $data = array('title' => 'Data Barang', 'f' => $f, 'barang' => $this->Barang_model->get_all($f));
        $this->ref($data);
        $this->render('barang/index', $data);
    }

    // ===== Validasi =====
    private function rules($id)
    {
        $fv = $this->form_validation;
        $fv->set_rules('kode_barang', 'Kode barang', 'required|trim|max_length[30]|callback__kode_unik[' . (int) $id . ']',
            array('required' => '%s wajib diisi.'));
        $fv->set_rules('nama_barang', 'Nama barang', 'required|trim|max_length[150]',
            array('required' => '%s wajib diisi.'));
        foreach (array('kategori_id' => 'Kategori', 'lokasi_id' => 'Lokasi', 'kondisi_id' => 'Kondisi') as $f => $label) {
            $tbl = str_replace('_id', '', $f);
            $fv->set_rules($f, $label, 'required|callback__ada[' . $tbl . ']',
                array('required' => '%s harus dipilih.'));
        }
        $fv->set_rules('satuan', 'Satuan', 'required|trim|max_length[20]');
        $fv->set_rules('keterangan', 'Keterangan', 'trim|max_length[255]');
        if (!$id) {
            $fv->set_rules('stok', 'Jumlah barang', 'required|numeric|is_natural',
                array(
                    'required'   => '%s wajib diisi.',
                    'numeric'    => '%s harus berupa angka.',
                    'is_natural' => '%s tidak boleh negatif dan harus bilangan bulat.',
                ));
        }
    }

    public function _kode_unik($str, $id)
    {
        if ($this->Barang_model->kode_ada($str, (int) $id)) {
            $this->form_validation->set_message('_kode_unik', 'Kode barang sudah digunakan, harus unik.');
            return FALSE;
        }
        return TRUE;
    }

    private function fields()
    {
        return array(
            'kode_barang' => $this->input->post('kode_barang', TRUE),
            'nama_barang' => $this->input->post('nama_barang', TRUE),
            'kategori_id' => (int) $this->input->post('kategori_id'),
            'lokasi_id'   => (int) $this->input->post('lokasi_id'),
            'kondisi_id'  => (int) $this->input->post('kondisi_id'),
            'satuan'      => $this->input->post('satuan', TRUE),
            'keterangan'  => $this->input->post('keterangan', TRUE),
        );
    }

    // ===== Tambah =====
    public function tambah()
    {
        $this->rules(0);
        if ($this->form_validation->run()) {
            $d = $this->fields();
            $d['stok'] = (int) $this->input->post('stok');
            if ($this->Barang_model->tambah($d, $this->session->userdata('user_id'))) {
                $this->session->set_flashdata('success', 'Barang berhasil ditambahkan.');
                redirect('barang');
            }
            $this->session->set_flashdata('error', 'Gagal menyimpan barang.');
        }
        $data = array('title' => 'Tambah Barang', 'barang' => NULL);
        $this->ref($data);
        $this->render('barang/form', $data);
    }

    // ===== Ubah =====
    public function edit($id = 0)
    {
        $barang = $this->Barang_model->find($id);
        if (!$barang) {
            show_404();
        }
        $this->rules($barang->id);
        if ($this->form_validation->run()) {
            if ($this->Barang_model->ubah($barang->id, $this->fields(), $barang, $this->session->userdata('user_id'))) {
                $this->session->set_flashdata('success', 'Data barang berhasil diperbarui.');
                redirect('barang');
            }
            $this->session->set_flashdata('error', 'Gagal memperbarui barang.');
        }
        $data = array('title' => 'Ubah Barang', 'barang' => $barang);
        $this->ref($data);
        $this->render('barang/form', $data);
    }

    // ===== Hapus (sesuai aturan) =====
    public function hapus($id = 0)
    {
        $this->must_post();
        $barang = $this->Barang_model->find($id);
        if (!$barang) {
            show_404();
        }
        if ($this->Barang_model->dipakai($barang->id)) {
            $this->session->set_flashdata('error',
                'Barang "' . $barang->nama_barang . '" tidak dapat dihapus karena sudah dipakai dalam transaksi barang masuk/keluar.');
        } else {
            $this->Barang_model->hapus($barang->id);
            $this->session->set_flashdata('success', 'Barang berhasil dihapus.');
        }
        redirect('barang');
    }
}