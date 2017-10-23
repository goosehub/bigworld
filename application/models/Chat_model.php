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

    function new_message($user_key, $username, $color, $ip, $message, $room_key)
    {
        $data = array(
            'user_key' => $user_key,
            'username' => $username,
            'color' => $color,
            'ip' => $ip,
            'message' => $message,
            'room_key' => $room_key,
            'timestamp' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('message', $data);
    }

    function recent_messages_by_ip($ip, $message_limit_length)
    {
        $ip = mysqli_real_escape_string(get_mysqli(), $ip);
        $message_limit_length = mysqli_real_escape_string(get_mysqli(), $message_limit_length);
        $query = $this->db->query("
            SELECT COUNT(id) as recent_messages
            FROM `message`
            WHERE `ip` = '" . $ip . "'
            AND `timestamp` > (now() - INTERVAL " . $message_limit_length . " SECOND);
        ");
        $result = $query->result_array();
        return isset($result[0]['recent_messages']) ? $result[0]['recent_messages'] : false;
    }
}
?>