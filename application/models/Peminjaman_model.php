<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model
{
    private function base()
    {
        $this->db->select('p.*, b.kode_barang, b.nama_barang, b.satuan, u.nama AS nama_pelanggan')
            ->from('peminjaman p')
            ->join('barang b', 'b.id = p.barang_id')
            ->join('users u', 'u.id = p.user_id');
    }

    public function find($id)
    {
        $this->base();
        return $this->db->where('p.id', (int) $id)->get()->row();
    }

    public function semua($status = '')
    {
        $this->base();
        if ($status) {
            $this->db->where('p.status', $status);
        }
        return $this->db->order_by('p.id', 'DESC')->get()->result();
    }

    public function milik($uid)
    {
        $this->base();
        return $this->db->where('p.user_id', (int) $uid)->order_by('p.id', 'DESC')->get()->result();
    }

    public function ajukan($d)
    {
        $d['status'] = 'menunggu';
        $d['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('peminjaman', $d);
    }

    public function set_status($id, $status, $extra = array())
    {
        $extra['status'] = $status;
        return $this->db->where('id', (int) $id)->update('peminjaman', $extra);
    }

    // Pelanggan hanya boleh membatalkan pengajuan miliknya yang masih menunggu
    public function batal($id, $uid)
    {
        $this->db->where(array('id' => (int) $id, 'user_id' => (int) $uid, 'status' => 'menunggu'))
            ->update('peminjaman', array('status' => 'dibatalkan'));
        return $this->db->affected_rows() > 0;
    }

    public function stat($uid)
    {
        $s = array('menunggu' => 0, 'disetujui' => 0, 'dikembalikan' => 0);
        $rows = $this->db->query('SELECT status, COUNT(*) n FROM peminjaman WHERE user_id = ? GROUP BY status', array((int) $uid))->result();
        foreach ($rows as $r) {
            if (isset($s[$r->status])) {
                $s[$r->status] = (int) $r->n;
            }
        }
        return $s;
    }
}