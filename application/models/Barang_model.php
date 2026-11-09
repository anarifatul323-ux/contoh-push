<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model
{
    private function base()
    {
        $this->db->select('b.*, k.nama_kategori, l.nama_lokasi, kd.nama_kondisi')
            ->from('barang b')
            ->join('kategori k', 'k.id = b.kategori_id', 'left')
            ->join('lokasi l', 'l.id = b.lokasi_id', 'left')
            ->join('kondisi kd', 'kd.id = b.kondisi_id', 'left');
    }

    public function get_all($f = array())
    {
        $this->base();
        if (!empty($f['q'])) {
            $this->db->group_start()
                ->like('b.nama_barang', $f['q'])
                ->or_like('b.kode_barang', $f['q'])
                ->group_end();
        }
        foreach (array('kategori_id', 'lokasi_id', 'kondisi_id') as $k) {
            if (!empty($f[$k])) {
                $this->db->where('b.' . $k, (int) $f[$k]);
            }
        }
        return $this->db->order_by('b.kode_barang')->get()->result();
    }

    public function find($id)
    {
        $this->base();
        return $this->db->where('b.id', (int) $id)->get()->row();
    }

    public function kode_ada($kode, $ignore = 0)
    {
        $this->db->where('kode_barang', $kode);
        if ($ignore) {
            $this->db->where('id !=', $ignore);
        }
        return $this->db->count_all_results('barang') > 0;
    }

    public function tambah($d, $user_id)
    {
        $this->db->trans_start();
        $this->db->insert('barang', $d);
        $id = $this->db->insert_id();
        $this->catat($id, 'stok_awal', $d['stok'], 0, $d['stok'], 'Data barang ditambahkan', $user_id);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Stok tidak diubah lewat sini (hanya lewat barang masuk/keluar)
    public function ubah($id, $d, $lama, $user_id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id)->update('barang', $d);

        if ((int) $lama->kondisi_id !== (int) $d['kondisi_id']) {
            $baru = $this->db->get_where('kondisi', array('id' => $d['kondisi_id']))->row();
            $this->catat($id, 'ubah_kondisi', 0, $lama->stok, $lama->stok,
                'Kondisi dari ' . $lama->nama_kondisi . ' menjadi ' . $baru->nama_kondisi, $user_id);
        }
        if ((int) $lama->lokasi_id !== (int) $d['lokasi_id']) {
            $baru = $this->db->get_where('lokasi', array('id' => $d['lokasi_id']))->row();
            $this->catat($id, 'pindah_lokasi', 0, $lama->stok, $lama->stok,
                'Lokasi dari ' . $lama->nama_lokasi . ' ke ' . $baru->nama_lokasi, $user_id);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

       public function dipakai($id)
    {
        $m = $this->db->where('barang_id', $id)->count_all_results('barang_masuk');
        $k = $this->db->where('barang_id', $id)->count_all_results('barang_keluar');
        $p = $this->db->where('barang_id', $id)->count_all_results('peminjaman');
        return ($m + $k + $p) > 0;
    }

    public function hapus($id)
    {
        return $this->db->where('id', $id)->delete('barang');
    }

    public function catat($barang_id, $jenis, $jumlah, $sebelum, $sesudah, $ket, $user_id)
    {
        return $this->db->insert('riwayat', array(
            'barang_id'    => $barang_id,
            'jenis'        => $jenis,
            'jumlah'       => $jumlah,
            'stok_sebelum' => $sebelum,
            'stok_sesudah' => $sesudah,
            'keterangan'   => $ket,
            'user_id'      => $user_id,
            'created_at'   => date('Y-m-d H:i:s'),
        ));
    }

    public function riwayat($f = array(), $limit = NULL)
    {
        $this->db->select('r.*, b.kode_barang, b.nama_barang, b.satuan, u.nama AS nama_user')
            ->from('riwayat r')
            ->join('barang b', 'b.id = r.barang_id')
            ->join('users u', 'u.id = r.user_id', 'left');
        if (!empty($f['barang_id'])) {
            $this->db->where('r.barang_id', (int) $f['barang_id']);
        }
        if (!empty($f['jenis'])) {
            $this->db->where('r.jenis', $f['jenis']);
        }
        if (!empty($f['dari'])) {
            $this->db->where('DATE(r.created_at) >=', $f['dari']);
        }
        if (!empty($f['sampai'])) {
            $this->db->where('DATE(r.created_at) <=', $f['sampai']);
        }
        $this->db->order_by('r.id', 'DESC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result();
    }

    public function stok_menipis($batas = 5)
    {
        $this->base();
        return $this->db->where('b.stok <=', $batas)->order_by('b.stok')->limit(10)->get()->result();
    }

    public function statistik()
    {
        $bulan = date('Y-m');
        $s = new stdClass();
        $s->jenis = $this->db->count_all('barang');
        $s->total_stok = (int) $this->db->select_sum('stok')->get('barang')->row()->stok;
        $s->masuk_bulan = (int) $this->db->query(
            "SELECT COALESCE(SUM(jumlah),0) n FROM barang_masuk WHERE DATE_FORMAT(tanggal,'%Y-%m') = ?", array($bulan))->row()->n;
        $s->keluar_bulan = (int) $this->db->query(
            "SELECT COALESCE(SUM(jumlah),0) n FROM barang_keluar WHERE DATE_FORMAT(tanggal,'%Y-%m') = ?", array($bulan))->row()->n;
        return $s;
    }

    // Rekap per kategori / lokasi / kondisi
    public function rekap($t)
    {
        if (!in_array($t, array('kategori', 'lokasi', 'kondisi'), TRUE)) {
            return array();
        }
        return $this->db->query(
            "SELECT t.id, t.nama_{$t} AS nama, COUNT(b.id) AS jenis, COALESCE(SUM(b.stok),0) AS total_stok
             FROM {$t} t LEFT JOIN barang b ON b.{$t}_id = t.id
             GROUP BY t.id, t.nama_{$t} ORDER BY t.id")->result();
    }

    public function rekap_barang()
    {
        return $this->db->query(
            "SELECT b.*, k.nama_kategori, l.nama_lokasi, kd.nama_kondisi,
                COALESCE((SELECT SUM(jumlah) FROM barang_masuk  WHERE barang_id = b.id),0) AS total_masuk,
                COALESCE((SELECT SUM(jumlah) FROM barang_keluar WHERE barang_id = b.id),0) AS total_keluar
             FROM barang b
             LEFT JOIN kategori k ON k.id = b.kategori_id
             LEFT JOIN lokasi l ON l.id = b.lokasi_id
             LEFT JOIN kondisi kd ON kd.id = b.kondisi_id
             ORDER BY b.kode_barang")->result();
    }
}