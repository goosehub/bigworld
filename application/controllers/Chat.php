<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);
        $this->load->model('chat_model', '', TRUE);
    }

    public function load()
    {
        // Set parameters
        $room_key = $this->input->post('room_key');
        $inital_load = $this->input->post('inital_load') === 'true' ? true : false;
        $last_message_id = $this->input->post('last_message_id');
        if ($inital_load) {
            $limit = 500;
        }
        else {
            $limit = 5;
        }

        // Authentication
        // $data['user'] = $this->user_model->get_this_user();

        // Update user last load
        // $this->user_model->update_user_last_load($user['id']);
        // $this->room_model->update_room_last_load($user['room_key']);

        // Get messages
        if ($inital_load) {
            $data['messages'] = $this->chat_model->load_message_by_limit($room_key, $limit);
            // Reverse array for ascending order (Could refactor into sql)
            $data['messages'] = array_reverse($data['messages']);
        }
        else {
            $data['messages'] = $this->chat_model->load_message_by_last_message_id($room_key, $last_message_id);
        }

        // htmlspecialchars is used inside api_response
        echo api_response($data);
    }

    public function new_message()
    {
        // Validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message_input', 'Message', 'trim|required|max_length[3000]');
        $this->form_validation->set_rules('room_key', 'Room Key', 'trim|required|max_length[11]');

        if ($this->form_validation->run() == FALSE) {
            echo strip_tags(validation_errors());
            return false;
        }

        // Authentication
        $user = $this->user_model->get_this_user();

        // Limit number of new messages in a timespan
        $ip = $_SERVER['REMOTE_ADDR'];
        $recent_messages = $this->chat_model->recent_messages_by_ip($ip, MESSAGE_SPAM_LIMIT_LENGTH);
        if (!is_dev() && $recent_messages > MESSAGE_SPAM_LIMIT_AMOUNT) {
            echo 'Your talking too much';
            return false;
        }

        // Anonymous users
        if (!$user) {
            $user['id'] = ANONYMOUS_USER_ID;
            $user['username'] = 'Anonymous';
            // Anonymous color is session based
            $user['color'] = $this->session->userdata('color');
            if (!$user['color']) {
                $user['color'] = random_hex_color();
                $this->session->set_userdata('color', $user['color']);
            }
        }

        // htmlspecialchars is used inside api_response on output
        $message = $this->input->post('message_input');
        $room_key = $this->input->post('room_key');
        $world_key = $this->input->post('world_key');

        // Get most recent message
        $most_recent_message = $this->chat_model->get_last_message_in_room($room_key);

        // If it's been a while since last message, system message on time
        if ($most_recent_message && strtotime($most_recent_message['timestamp']) + MINUTES_BETWEEN_MESSAGES_TO_SHOW_DATE * 60 < time()) {
            $date_message = gmdate('g:i A M dS Y T');
            $this->chat_model->new_message(SYSTEM_USER_ID, SYSTEM_DATE_USERNAME, '#000000', '', $date_message, $room_key, $world_key);
        }

        // Insert message
        $this->chat_model->new_message($user['id'], $user['username'], $user['color'], $ip, $message, $room_key, $world_key);

        // Update room last new message
        $this->room_model->update_room_last_message_time($room_key);
    }

    public function report($message_id)
    {
        // Limiting
        $report_requests = $this->main_model->count_requests_by_route($_SERVER['REMOTE_ADDR'], 'chat/report', date('Y-m-d H:i:s', time() - REPORT_SPAM_LIMIT_LENGTH));
        if ($report_requests > REPORT_SPAM_LIMIT_AMOUNT) {
            $this->load->view('errors/html/failed_report');
            return;
        }

        $this->main_model->record_request();
        $this->chat_model->increment_report_count($message_id);
        $this->load->view('templates/post_report');
    }
}