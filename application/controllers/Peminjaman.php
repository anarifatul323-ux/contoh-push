<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Peminjaman_model', 'Transaksi_model'));
    }

    public function index()
    {
        $status = $this->input->get('status', TRUE);
        $this->render('peminjaman/index', array(
            'title'  => 'Peminjaman Barang',
            'status' => $status,
            'daftar' => $this->Peminjaman_model->semua($status),
        ));
    }

    public function setujui($id = 0)
    {
        $this->must_post();
        $p = $this->Peminjaman_model->find($id);
        if (!$p || $p->status !== 'menunggu') {
            $this->session->set_flashdata('error', 'Pengajuan tidak ditemukan atau sudah diproses.');
            redirect('peminjaman');
        }
        $res = $this->Transaksi_model->keluar(array(
            'barang_id'  => $p->barang_id,
            'jumlah'     => $p->jumlah,
            'tanggal'    => date('Y-m-d'),
                        'keterangan' => 'Dipinjam oleh ' . $p->nama_pelanggan,
            'user_id'    => $this->session->userdata('user_id'),
        ));
        if ($res[0]) {
            $this->Peminjaman_model->set_status($p->id, 'disetujui');
            $this->session->set_flashdata('success', 'Peminjaman disetujui. ' . $res[1]);
        } else {
            $this->session->set_flashdata('error', 'Tidak bisa disetujui. ' . $res[1]);
        }
        redirect('peminjaman');
    }

    public function tolak($id = 0)
    {
        $this->must_post();
        $p = $this->Peminjaman_model->find($id);
        if ($p && $p->status === 'menunggu') {
            $this->Peminjaman_model->set_status($p->id, 'ditolak', array(
                'catatan_admin' => $this->input->post('catatan', TRUE),
            ));
            $this->session->set_flashdata('success', 'Peminjaman ditolak.');
        } else {
            $this->session->set_flashdata('error', 'Pengajuan tidak ditemukan atau sudah diproses.');
        }
        redirect('peminjaman');
    }

    public function kembali($id = 0)
    {
        $this->must_post();
        $p = $this->Peminjaman_model->find($id);
        if (!$p || $p->status !== 'disetujui') {
            $this->session->set_flashdata('error', 'Data peminjaman tidak valid untuk dikembalikan.');
            redirect('peminjaman');
        }
        $res = $this->Transaksi_model->masuk(array(
            'barang_id'  => $p->barang_id,
            'jumlah'     => $p->jumlah,
            'tanggal'    => date('Y-m-d'),
                        'keterangan' => 'Dikembalikan oleh ' . $p->nama_pelanggan,
            'user_id'    => $this->session->userdata('user_id'),
        ));
        if ($res[0]) {
            $this->Peminjaman_model->set_status($p->id, 'dikembalikan', array('tanggal_dikembalikan' => date('Y-m-d')));
            $this->session->set_flashdata('success', 'Barang dikembalikan. ' . $res[1]);
        } else {
            $this->session->set_flashdata('error', $res[1]);
        }
        redirect('peminjaman');
    }
}