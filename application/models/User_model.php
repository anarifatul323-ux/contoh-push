<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function by_username($username)
    {
        return $this->db->get_where('users', array('username' => $username))->row();
    }

    public function all()
    {
        return $this->db->order_by('id')->get('users')->result();
    }

    public function insert($d)
    {
        return $this->db->insert('users', $d);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('users');
    }
}