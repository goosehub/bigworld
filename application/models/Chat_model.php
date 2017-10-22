<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class chat_model extends CI_Model
{
    function load_message_by_limit($room_key, $limit)
    {
        $this->db->select('*');
        $this->db->from('message');
        $this->db->where('room_key', $room_key);
        $this->db->limit($limit);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function load_message_by_last_message_id($room_key, $last_message_id)
    {
        $this->db->select('*');
        $this->db->from('message');
        $this->db->where('room_key', $room_key);
        $this->db->where('id >', $last_message_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function new_message($user_key, $username, $color, $message, $room_key)
    {
        $data = array(
            'user_key' => $user_key,
            'username' => $username,
            'color' => $color,
            'message' => $message,
            'room_key' => $room_key,
            'timestamp' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('message', $data);
    }
}
?>