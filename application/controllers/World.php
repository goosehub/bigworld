<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class World extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('world_model', '', TRUE);

        $this->main_model->record_request();
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
        if (!isset($input->world_id)) {
            echo api_error_response('world_id_missing', 'World id is a required parameter and was not provided.');
            return false;
        }

        // Check if room is already a favorite
        $get_favorite = $this->world_model->get_favorite_world($user['id'], $input->world_id);

        // If current favorite, delete favorite
        if ($get_favorite) {
            $this->world_model->delete_favorite_world($user['id'], $input->world_id);
        }
        // If not current favorite, insert favorite
        else {
            $this->world_model->create_favorite_world($user['id'], $input->world_id);
        }

        // Respond
        echo api_response(array());
    }
}