<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Model generik untuk tabel master: kategori, lokasi, kondisi
class Master_model extends CI_Model
{
    public function all($t)
    {
        return $this->db->order_by('id')->get($t)->result();
    }

    public function find($t, $id)
    {
        return $this->db->get_where($t, array('id' => $id))->row();
    }

    public function exists($t, $field, $val, $ignore = 0)
    {
        $this->db->where($field, $val);
        if ($ignore) {
            $this->db->where('id !=', $ignore);
        }
        return $this->db->count_all_results($t) > 0;
    }

    public function simpan($t, $d)
    {
        return $this->db->insert($t, $d);
    }

    public function ubah($t, $id, $d)
    {
        return $this->db->where('id', $id)->update($t, $d);
    }

    public function hapus($t, $id)
    {
        return $this->db->where('id', $id)->delete($t);
    }

    // Apakah data master masih dipakai oleh tabel barang?
    public function dipakai($fk, $id)
    {
        return $this->db->where($fk, $id)->count_all_results('barang') > 0;
    }
}