<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class room_model extends CI_Model
{
    function insert_room($data)
    {
        $this->db->insert('room', $data);
        $room_id = $this->db->insert_id();

        // Create system start message
        $message = $this->system_start_room_message();
        $result = $this->chat_model->new_message(SYSTEM_USER_ID, SYSTEM_START_ROOM_USERNAME, '#000000', '', $message, $room_id);
        
        return $room_id;

    }

    function system_start_room_message()
    {
        $message = "";
        $message .= "Welcome to your room! Some tips: Embed Youtube, Vimeo, Twitch, SoundCloud, Vocaroo, and Images by posting the URL. Pin posts to keep in view as you chat. Share this url to invite others to join directly.";
        return $message;
    }

    function get_all_rooms()
    {
        $this->db->select('*');
        $this->db->from('room');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_room_by_id($room_id)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('id', $room_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function get_rooms_by_user_key($user_key)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('user_key', $user_key);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}
?>