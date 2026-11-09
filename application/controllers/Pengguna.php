<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->only_admin();
        $this->load->model('User_model');
    }

    public function index()
    {
        $fv = $this->form_validation;
        $fv->set_rules('nama', 'Nama', 'required|trim|max_length[100]');
        $fv->set_rules('username', 'Username', 'required|trim|alpha_dash|min_length[4]|max_length[50]|is_unique[users.username]',
            array('is_unique' => 'Username sudah dipakai.'));
        $fv->set_rules('password', 'Password', 'required|min_length[6]');
        $fv->set_rules('passconf', 'Konfirmasi password', 'required|matches[password]',
            array('matches' => 'Konfirmasi password tidak sama.'));
        $fv->set_rules('role', 'Peran', 'required|in_list[admin,petugas]');

        if ($fv->run()) {
            $this->User_model->insert(array(
                'nama'     => $this->input->post('nama', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role'     => $this->input->post('role'),
            ));
            $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
            redirect('pengguna');
        }
        $this->render('pengguna/index', array('title' => 'Data Pengguna', 'users' => $this->User_model->all()));
    }

    public function hapus($id = 0)
    {
        $this->must_post();
        if ((int) $id === (int) $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun yang sedang dipakai.');
        } else {
            $this->User_model->delete((int) $id);
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        }
        redirect('pengguna');
    }
}