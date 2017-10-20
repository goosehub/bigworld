<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Room extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);

        $this->main_model->record_request();
    }

    public function create()
    {
        // Authentication
        $user = $this->user_model->get_this_user();
        if (!$user) {
            echo api_error_response('not_logged_in', 'You must be logged in to create a room');
            return false;
        }

        // Validate input
        $input = get_json_post(true);
        if (!isset($input->room_name)) {
            echo api_error_response('room_name_missing', 'Room name is a required parameter and was not provided.');
            return false;
        }
        if (!isset($input->lat)) {
            echo api_error_response('lat_missing', 'lat is a required parameter and was not provided.');
            return false;
        }
        if (!isset($input->lng)) {
            echo api_error_response('lng_missing', 'lng is a required parameter and was not provided.');
            return false;
        }
        $max_length_room_name = 40;
        if (strlen($input->room_name) > $max_length_room_name) {
            echo api_error_response('room_name_too_long', 'Room name must be ' . $max_length_room_name . ' characters or shorter.');
            return false;
        }
        // Lat and Lng must be valid geo locations

        // Check if user is at maximum number of rooms

        // Create room
        $room = array();
        $room['name'] = $input->room_name;
        $room['lat'] = number_format($input->lat, 4);
        $room['lng'] = number_format($input->lng, 4);
        $room['last_message_time'] = date('Y-m-d H:i:s');
        $room['created'] = date('Y-m-d H:i:s');
        $room['user_key'] = $user['id'];

        // Insert room
        $room['id'] = $this->room_model->insert_room($room);

        // Respond
        echo api_response($room);
    }
}