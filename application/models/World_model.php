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
        // $this->db->order_by('rand()');
        $this->db->order_by('last_load', 'desc');
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

        // Update world last_load
        $data = array(
            'last_load' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $result[0]['id']);
        $this->db->update('world', $data);

        // Return world
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


        // Update world last_load
        $data = array(
            'last_load' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $result[0]['id']);
        $this->db->update('world', $data);

        // Return world
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

    function get_favorite_world($user_key, $world_key)
    {
        $this->db->select('*');
        $this->db->from('favorite_world');
        $this->db->where('user_key', $user_key);
        $this->db->where('world_key', $world_key);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function delete_favorite_world($user_key, $world_key)
    {
        $this->db->where('user_key', $user_key);
        $this->db->where('world_key', $world_key);
        $this->db->delete('favorite_world');
    }

    function create_favorite_world($user_key, $world_key)
    {
        $data = array(
            'user_key' => $user_key,
            'world_key' => $world_key,
            'created' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('favorite_world', $data);
    }

    function get_favorite_worlds_by_user_key($user_key)
    {
        $this->db->select('*');
        $this->db->from('world');
        $this->db->where('favorite_world.user_key', $user_key);
        $this->db->join('favorite_world', 'favorite_world.world_key = world.id', 'right');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

}
?>