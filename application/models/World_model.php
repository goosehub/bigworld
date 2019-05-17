<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class world_model extends CI_Model
{
    function insert_world($data)
    {
        $this->db->insert('world', $data);
    }

    function get_all_worlds()
    {
        $this->db->select('*');
        $this->db->from('world');
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_all_worlds_by_last_activity($last_activity_in_minutes)
    {
        $this->db->select('*');
        $this->db->from('world');
        $this->db->where('archived', 0);
        $this->db->where('last_message_time >', date('Y-m-d H:i:s', time() - $last_activity_in_minutes * 60));
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_world_by_id($world_id)
    {
        $this->db->select('*');
        $this->db->from('world');
        $this->db->where('id', $world_id);
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function get_world_by_slug($world_slug)
    {
        $this->db->select('*');
        $this->db->from('world');
        $this->db->where('slug', $world_slug);
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function get_worlds_by_user_key($user_key)
    {
        $this->db->select('*');
        $this->db->from('world');
        $this->db->where('user_key', $user_key);
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

}
?>