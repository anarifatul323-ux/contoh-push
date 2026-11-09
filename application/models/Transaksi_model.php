<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
    }

    public function masuk($d)
    {
        return $this->gerak('masuk', $d);
    }

    public function keluar($d)
    {
        return $this->gerak('keluar', $d);
    }

    // Satu proses atomik: kunci baris barang, cek stok, simpan transaksi, update stok, catat riwayat
    private function gerak($jenis, $d)
    {
        $this->db->trans_begin();

        $b = $this->db->query('SELECT id, stok, satuan FROM barang WHERE id = ? FOR UPDATE', array($d['barang_id']))->row();
        if (!$b) {
            $this->db->trans_rollback();
            return array(FALSE, 'Barang tidak ditemukan.');
        }

        $sebelum = (int) $b->stok;
        $jml = (int) $d['jumlah'];

        if ($jenis === 'keluar' && $jml > $sebelum) {
            $this->db->trans_rollback();
            return array(FALSE, 'Stok tidak mencukupi. Stok tersedia ' . $sebelum . ' ' . $b->satuan . ', diminta ' . $jml . '.');
        }

        $sesudah = ($jenis === 'masuk') ? $sebelum + $jml : $sebelum - $jml;

        $this->db->insert('barang_' . $jenis, $d);
        $this->db->where('id', $d['barang_id'])->update('barang', array('stok' => $sesudah));
        $this->Barang_model->catat($d['barang_id'], $jenis, $jml, $sebelum, $sesudah,
            !empty($d['keterangan']) ? $d['keterangan'] : ucfirst('barang ' . $jenis), $d['user_id']);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(FALSE, 'Gagal menyimpan transaksi.');
        }
        $this->db->trans_commit();
        return array(TRUE, 'Transaksi berhasil. Stok sekarang: ' . $sesudah . ' ' . $b->satuan . '.');
    }

    public function daftar($jenis, $limit = 20)
    {
        if (!in_array($jenis, array('masuk', 'keluar'), TRUE)) {
            return array();
        }
        return $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, u.nama AS nama_user')
            ->from('barang_' . $jenis . ' t')
            ->join('barang b', 'b.id = t.barang_id')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->order_by('t.id', 'DESC')->limit($limit)->get()->result();
    }
}