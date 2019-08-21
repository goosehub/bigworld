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

    function load_unread_pm_rooms($user_key)
    {
        $user_key = (int)$user_key;
        $this->db->select('*');
        $this->db->from('room');
        $this->db->where('(user_key = ' . $user_key . ' AND user_unread = 1) OR (receiving_user_key = ' . $user_key . ' AND receiving_user_unread = 1) AND is_pm = 1', NULL, FALSE);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function mark_pm_room_as_read($room_key, $user_key)
    {
        $room_key = (int)$room_key; 
        $user_key = (int)$user_key;
        $data = array(
            'user_unread' => 0,
            'receiving_user_unread' => 0,
        );
        $this->db->where('( (user_key = ' . $user_key . ' AND user_unread = 1) OR (receiving_user_key = ' . $user_key . ' AND receiving_user_unread = 1) ) AND is_pm = 1 AND id = ' . $room_key, NULL, FALSE);
        $this->db->update('room', $data);
    }

    function mark_pm_room_as_unread($room_key, $user_key)
    {
        $room_key = (int)$room_key; 
        $user_key = (int)$user_key;

        $data = array(
            'receiving_user_unread' => 1,
        );
        // $this->db->where('(user_key = ' . $user_key . ' AND user_unread = 0) AND is_pm = 1 AND id = ' . $room_key, NULL, FALSE);
        $this->db->where('user_key', $user_key);
        $this->db->where('user_unread', 0);
        $this->db->where('is_pm', 1);
        $this->db->where('id', $room_key);
        $this->db->update('room', $data);

        $data = array(
            'user_unread' => 1,
        );
        // $this->db->where('(receiving_user_key = ' . $user_key . ' AND receiving_user_unread = 0) AND is_pm = 1 AND id = ' . $room_key, NULL, FALSE);
        $this->db->where('receiving_user_key', $user_key);
        $this->db->where('receiving_user_unread', 0);
        $this->db->where('is_pm', 1);
        $this->db->where('id', $room_key);
        $this->db->update('room', $data);
    }

    function get_last_message_in_room($room_key)
    {
        $this->db->select('*');
        $this->db->from('message');
        $this->db->where('room_key', $room_key);
        $this->db->limit(1);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]) ? $result[0] : false;
    }

    function new_message($user_key, $username, $color, $ip, $message, $room_key, $world_key)
    {
        $data = array(
            'user_key' => $user_key,
            'username' => $username,
            'color' => $color,
            'ip' => $ip,
            'message' => $message,
            'room_key' => $room_key,
            'world_key' => $world_key,
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

    function increment_report_count($message_id)
    {
        $this->db->where('id', $message_id);
        $this->db->set('report_count', 'report_count+1', FALSE);
        $this->db->update('message');
    }
}
?>