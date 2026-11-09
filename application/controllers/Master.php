<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Satu controller untuk 3 data master: kategori, lokasi, kondisi
class Master extends MY_Controller
{
    private $cfg = array(
        'kategori' => array('table' => 'kategori', 'field' => 'nama_kategori', 'label' => 'Kategori',       'fk' => 'kategori_id'),
        'lokasi'   => array('table' => 'lokasi',   'field' => 'nama_lokasi',   'label' => 'Lokasi',         'fk' => 'lokasi_id'),
        'kondisi'  => array('table' => 'kondisi',  'field' => 'nama_kondisi',  'label' => 'Kondisi Barang', 'fk' => 'kondisi_id'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model');
    }

    private function cfg($type)
    {
        if (!isset($this->cfg[$type])) {
            show_404();
        }
        return $this->cfg[$type];
    }

    private function rules($type, $id)
    {
        $c = $this->cfg[$type];
        $this->form_validation->set_rules($c['field'], 'Nama ' . strtolower($c['label']),
            'required|trim|max_length[100]|callback__unik[' . $type . '.' . (int) $id . ']',
            array('required' => '%s wajib diisi.'));
    }

    public function _unik($str, $param)
    {
        list($type, $id) = explode('.', $param);
        $c = $this->cfg[$type];
        if ($this->Master_model->exists($c['table'], $c['field'], $str, (int) $id)) {
            $this->form_validation->set_message('_unik', 'Nama sudah ada, tidak boleh sama.');
            return FALSE;
        }
        return TRUE;
    }

    private function page($type, $edit = NULL)
    {
        $c = $this->cfg($type);
        $this->render('master/index', array(
            'title' => 'Data ' . $c['label'],
            'type'  => $type,
            'c'     => $c,
            'items' => $this->Master_model->all($c['table']),
            'edit'  => $edit,
        ));
    }

    public function index($type = 'kategori')
    {
        $c = $this->cfg($type);
        $this->rules($type, 0);
        if ($this->form_validation->run()) {
            $this->Master_model->simpan($c['table'], array($c['field'] => $this->input->post($c['field'], TRUE)));
            $this->session->set_flashdata('success', $c['label'] . ' berhasil ditambahkan.');
            redirect('master/index/' . $type);
        }
        $this->page($type);
    }

    public function edit($type = 'kategori', $id = 0)
    {
        $c = $this->cfg($type);
        $row = $this->Master_model->find($c['table'], $id);
        if (!$row) {
            show_404();
        }
        $this->rules($type, $row->id);
        if ($this->form_validation->run()) {
            $this->Master_model->ubah($c['table'], $row->id, array($c['field'] => $this->input->post($c['field'], TRUE)));
            $this->session->set_flashdata('success', $c['label'] . ' berhasil diperbarui.');
            redirect('master/index/' . $type);
        }
        $this->page($type, $row);
    }

    public function hapus($type = 'kategori', $id = 0)
    {
        $this->must_post();
        $c = $this->cfg($type);
        if (!$this->Master_model->find($c['table'], $id)) {
            show_404();
        }
        if ($this->Master_model->dipakai($c['fk'], $id)) {
            $this->session->set_flashdata('error', 'Data tidak dapat dihapus karena masih dipakai oleh data barang.');
        } else {
            $this->Master_model->hapus($c['table'], $id);
            $this->session->set_flashdata('success', $c['label'] . ' berhasil dihapus.');
        }
        redirect('master/index/' . $type);
    }
}