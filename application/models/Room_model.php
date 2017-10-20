<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class room_model extends CI_Model
{
    function insert_room($data)
    {
        $this->db->insert('room', $data);
        return $this->db->insert_id();
    }
}
?>