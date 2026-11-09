<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller
{
      public function index()
    {
        $this->load->view('landing', array(
            'logged_in' => (bool) $this->session->userdata('logged_in'),
            'dash'      => $this->session->userdata('role') === 'pelanggan' ? 'pelanggan' : 'dashboard',
        ));
    }
}