<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class World extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('world_model', '', TRUE);

        $this->main_model->record_request();
    }

    public function create()
    {
        // Limiting
        $create_world_requests = $this->main_model->count_requests_by_route($_SERVER['REMOTE_ADDR'], 'world/create', date('Y-m-d H:i:s', time() - CREATE_WORLD_SPAM_LIMIT_LENGTH));
        if ($create_world_requests > CREATE_WORLD_SPAM_LIMIT_AMOUNT) {
            $this->session->set_flashdata('failed_form', 'create_world');
            $this->session->set_flashdata('validation_errors', 'Sorry, but too many worlds have been created from this IP. Please take a break and try again later.');
            redirect(base_url(), 'refresh');
            return false;
        }

        $user = $this->user_model->get_this_user();
        // If user not logged in, return with fail
        if (!$user) {
            echo 'not logged in';
            return;
        }

        // Basic Validation
        $this->load->library('form_validation');
        // May consider using alpha_dash instead for more flexibility
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|alpha_numeric|max_length[100]');
        
        // Fail Basic Validation
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('failed_form', 'create_world');
            $this->session->set_flashdata('validation_errors', validation_errors());
            redirect(base_url(), 'refresh');
            return false;
        }

        $slug = $this->input->post('slug');

        // Get World
        $data['world'] = $this->world_model->get_world_by_slug($slug);
        if ($data['world']) {
            redirect(base_url() . $slug, 'refresh');
            return;
        }

        // Set inputs
        $data = array();
        $data['slug'] = $slug;
        $data['user_key'] = $user['id'];
        $data['archived'] = 0;

        // Insert world
        $this->world_model->insert_world($data);

        // Redirect to homepage
        redirect(base_url() . $slug, 'refresh');
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