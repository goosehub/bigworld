<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class room_model extends CI_Model
{
    function insert_room($data)
    {
        $this->db->insert('room', $data);
        return $this->db->insert_id();
    }
    function get_all_rooms()
    {
        $this->db->select('*');
        $this->db->from('room');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}
?>