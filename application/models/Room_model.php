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
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_all_rooms_by_last_activity($last_activity_in_minutes)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('archived', 0);
        $this->db->where('last_message_time >', date('Y-m-d H:i:s', time() - $last_activity_in_minutes * 60));
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function get_room_by_id($room_id)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('id', $room_id);
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function get_room_by_location($lat, $lng)
    {
        // Double check these inputs just incase
        if (!is_numeric($lat) || !is_numeric($lng)) {
            die();
        }

        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('CAST(lat AS DECIMAL(12,4)) =', 'CAST(' . $lat . ' AS DECIMAL(12,4))', false);
        $this->db->where('CAST(lng AS DECIMAL(12,4)) =', 'CAST(' . $lng . ' AS DECIMAL(12,4))', false);
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function get_rooms_by_user_key($user_key)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('user_key', $user_key);
        $this->db->where('archived', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function update_room_last_message_time($room_id)
    {
        $data = array(
            'last_message_time' => date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $room_id);
        $this->db->update('room', $data);
    }

    function get_favorite($user_key, $room_key)
    {
        $this->db->select('*');
        $this->db->from('favorite');
        $this->db->where('user_key', $user_key);
        $this->db->where('room_key', $room_key);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function delete_favorite($user_key, $room_key)
    {
        $this->db->where('user_key', $user_key);
        $this->db->where('room_key', $room_key);
        $this->db->delete('favorite');
    }

    function create_favorite($user_key, $room_key)
    {
        $data = array(
            'user_key' => $user_key,
            'room_key' => $room_key,
            'created' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('favorite', $data);
    }

    function get_favorites_by_user_key($user_key)
    {
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('favorite.user_key', $user_key);
        $this->db->join('favorite', 'favorite.room_key = room.id', 'right');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function archive_inactive_rooms($room_trim_minutes_since_last_message)
    {
        $data = array(
            'archived' => 1,
        );
        $delete_time = time() - ($room_trim_minutes_since_last_message * 60);
        $this->db->where('last_message_time < ', date('Y-m-d H:i:s', $delete_time));
        $this->db->where('archived', 0);
        $this->db->update('room', $data);
    }

}
?>