<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);
        $this->load->model('chat_model', '', TRUE);

        $this->main_model->record_request();
    }

    public function load()
    {
        // Set parameters
        $room_key = $this->input->post('room_key');
        $room_key = 9;
        $inital_load = $this->input->post('inital_load') === 'true' ? true : false;
        $last_message_id = $this->input->post('last_message_id');
        if ($inital_load) {
            $limit = 500;
        }
        else {
            $limit = 5;
        }

        // Authentication
        $data['user'] = $this->user_model->get_this_user();

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

        echo api_response($data);
    }

    public function new_message()
    {
        // Validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message_input', 'Message', 'trim|max_length[3000]|callback_new_message_validation');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            return false;
        }

        // Authentication
        $user = $this->user_model->get_this_user();
        $user['color'] = '#ff0000';
        $room_key = 9;

        $message = htmlspecialchars($this->input->post('message_input'));

        // Insert message
        $result = $this->chat_model->new_message($user['id'], $user['username'], $user['color'], $message, $room_key);
    }

    // Message Callback
    public function new_message_validation()
    {
        // Authentication
        $user = $this->user_model->get_this_user();
        if (!$user) {
            $this->form_validation->set_message('new_message_validation', 'Your session has expired');
            return false;
        }
        if (!$this->input->post('message_input')) {
            $this->form_validation->set_message('new_message_validation', '');
            return false;
        }
        // Limit number of new messages in a timespan
        // $message_spam_limit_amount = 10;
        // $message_spam_limit_length = 60;
        // $recent_messages = $this->main_model->recent_messages($user['id'], $message_spam_limit_length);
        // if (!is_dev() && $recent_messages > $message_spam_limit_amount) {
            // $this->form_validation->set_message('new_message_validation', 'Your talking too much');
            // return false;
        // }

        return true;
    }
}