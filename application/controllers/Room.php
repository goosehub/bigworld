<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Room extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);
        $this->load->model('chat_model', '', TRUE);

        $this->main_model->record_request();
    }

    public function get_room($room_id = false)
    {
        // Validate input
        if (!$room_id) {
            echo api_error_response('room_id_missing', 'Room id is a required parameter and was not provided.');
            return false;
        }

        // Get room
        $room = $this->room_model->get_room_by_id($room_id);

        // Authentication
        $user = $this->user_model->get_this_user();
        $room['is_favorite'] = false;
        if ($user) {
            $room['is_favorite'] = $this->room_model->get_favorite_room($user['id'], $room_id);
        }

        // Handle room not found
        if (!$room) {
            echo api_error_response('room_not_found', 'That room was not found.');
            return false;
        }

        // Return room
        echo api_response($room);
    }

    public function create()
    {
        // Authentication
        $user = $this->user_model->get_this_user();

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
        if (!is_numeric($input->lat) || !is_numeric($input->lng)) {
            echo api_error_response('invalid_location', 'Location provided was invalid.');
            return false;
        }
        // Check that no room at that location exists
        $input->lat = number_format($input->lat, 4);
        $input->lng = number_format($input->lng, 4);
        $room_exists_at_location = $this->room_model->get_room_by_location($input->lat, $input->lng, $input->world_id);
        if ($room_exists_at_location) {
            echo api_error_response('room_exists_at_location', 'Room at that location already exists.');
            return false;
        }

        // Create room
        $room = array();
        $room['name'] = $input->room_name;
        $room['world_key'] = $input->world_key;
        $room['lat'] = $input->lat;
        $room['lng'] = $input->lng;
        $room['last_message_time'] = date('Y-m-d H:i:s');
        $room['created'] = date('Y-m-d H:i:s');
        $room['user_key'] = isset($user['id']) ? $user['id'] : null;
        // Insert room
        $room['id'] = $this->room_model->insert_room($room);

        // Respond
        echo api_response($room);
    }

    public function favorite()
    {
        // Authentication
        $user = $this->user_model->get_this_user();
        if (!$user) {
            echo api_error_response('not_logged_in', 'You must be logged in to favorite a room');
            return false;
        }

        // Validate input
        $input = get_json_post(true);
        if (!isset($input->room_id)) {
            echo api_error_response('room_id_missing', 'Room id is a required parameter and was not provided.');
            return false;
        }
        if (!isset($input->world_id)) {
            echo api_error_response('world_id_missing', 'World id is a required parameter and was not provided.');
            return false;
        }

        // Check if room is already a favorite
        $get_favorite = $this->room_model->get_favorite_room($user['id'], $input->room_id);

        // If current favorite, delete favorite
        if ($get_favorite) {
            $this->room_model->delete_favorite_room($user['id'], $input->room_id);
        }
        // If not current favorite, insert favorite
        else {
            $this->room_model->create_favorite_room($user['id'], $input->room_id, $input->world_id);
        }

        // Respond
        echo api_response(array());
    }
}