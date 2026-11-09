<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    private function home()
    {
        return $this->session->userdata('role') === 'pelanggan' ? 'pelanggan' : 'dashboard';
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect($this->home());
        }
        $data = array();
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {
            $u = $this->User_model->by_username($this->input->post('username', TRUE));
            if ($u && password_verify($this->input->post('password'), $u->password)) {
                $this->session->set_userdata(array(
                    'logged_in' => TRUE,
                    'user_id'   => $u->id,
                    'nama'      => $u->nama,
                    'role'      => $u->role,
                ));
                redirect($this->home());
            }
            $data['error'] = 'Username atau password salah.';
        }
        $this->load->view('auth/login', $data);
    }

    public function daftar()
    {
        if ($this->session->userdata('logged_in')) {
            redirect($this->home());
        }
        $fv = $this->form_validation;
        $fv->set_rules('nama', 'Nama', 'required|trim|max_length[100]');
        $fv->set_rules('username', 'Username', 'required|trim|alpha_dash|min_length[4]|max_length[50]|is_unique[users.username]',
            array('is_unique' => 'Username sudah dipakai.'));
        $fv->set_rules('password', 'Password', 'required|min_length[6]');
        $fv->set_rules('passconf', 'Konfirmasi password', 'required|matches[password]',
            array('matches' => 'Konfirmasi password tidak sama.'));

        if ($fv->run()) {
            $this->User_model->insert(array(
                'nama'     => $this->input->post('nama', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role'     => 'pelanggan',
            ));
            $this->session->set_flashdata('success', 'Pendaftaran berhasil, silakan masuk.');
            redirect('auth/login');
        }
        $this->load->view('auth/daftar');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}