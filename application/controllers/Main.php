<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);
        $this->load->model('world_model', '', TRUE);

        $this->main_model->record_request();
    }

    public function index()
    {
        // Authentication
        $data['user'] = $this->user_model->get_this_user();

        // Get Worlds
        $data['worlds'] = $this->world_model->get_all_worlds();

        // Load view
        $data = $this->registration_starting_details($data);
        $data['page_title'] = site_name();
        $data['landing'] = true;
        $this->load->view('templates/header', $data);
        $this->load->view('landing', $data);
        $this->load->view('login', $data);
        $this->load->view('scripts/interface_script', $data);
        $this->load->view('templates/footer', $data);
    }

    public function world($slug)
    {
        // Authentication
        $data['user'] = $this->user_model->get_this_user();

        // Get World
        $data['world'] = $this->world_model->get_world_by_slug($slug);
        if (!$data['world']) {
            echo 'no world';
            return;
        }

        // Get filters
        $data['filters'] = $this->get_filters();

        if ($data['user']) {
            // Include owned rooms
            $data['user']['rooms'] = $this->room_model->get_rooms_by_user_key($data['user']['id'], $data['world']['id']);
            
            // Include favorite_roomd rooms
            $data['user']['favorite_rooms'] = $this->room_model->get_favorite_rooms_by_user_key($data['user']['id'], $data['world']['id']);
        }

        // Use last activity default first
        $data['current_last_activity_filter'] = $data['filters'][LAST_ACTIVITY_DEFAULT];

        // Get last activity filter if exists
        if ($this->input->get('last_activity') && isset($data['filters'][$this->input->get('last_activity')])) {
            $data['current_last_activity_filter'] = $data['filters'][$this->input->get('last_activity')];
        }

        // Get rooms by last activity
        $data['rooms'] = $this->room_model->get_all_rooms_by_last_activity($data['current_last_activity_filter']['minutes_ago'], $data['world']['id']);

        // A/B testing
        $ab_array = array('', '');
        $data['ab_test'] = $ab_array[array_rand($ab_array)];

        // Registration starting details
        if (!$data['user']) {
            // Random color
            $data['random_color'] = random_hex_color();

            // Guess location
            $data['location_prepopulate'] = $this->guess_location();
        }

        // Validation errors
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $data['failed_form'] = $this->session->flashdata('failed_form');
        $data['just_registered'] = $this->session->flashdata('just_registered');

        // Load view
        $data = $this->registration_starting_details($data);
        $data['page_title'] = $slug;
        $data['landing'] = false;
        $this->load->view('templates/header', $data);
        $this->load->view('menus', $data);
        $this->load->view('blocks', $data);
        $this->load->view('room', $data);
        $this->load->view('login', $data);
        $this->load->view('scripts/map_script', $data);
        $this->load->view('scripts/chat_script', $data);
        $this->load->view('scripts/interface_script', $data);
        $this->load->view('templates/footer', $data);
    }

    public function registration_starting_details($data)
    {
        // Registration starting details
        if (!$data['user']) {
            // Random color
            $data['random_color'] = random_hex_color();

            // Guess location
            $data['location_prepopulate'] = $this->guess_location();
        }

        // Validation errors
        $data['validation_errors'] = $this->session->flashdata('validation_errors');
        $data['failed_form'] = $this->session->flashdata('failed_form');
        $data['just_registered'] = $this->session->flashdata('just_registered');

        return $data;
    }

    public function create_world()
    {
        $user = $this->user_model->get_this_user();
        // If user not logged in, return with fail
        if (!$user) {
            echo 'not logged in';
            return;
        }

        // Basic Validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('slug', 'Slug', 'trim|required|alpha|max_length[100]');
        
        // Fail Basic Validation
        if ($this->form_validation->run() == FALSE) {
            echo '{"error": "' . trim(strip_tags(validation_errors())) . '"}';
            return false;
        }

        $slug = $this->input->post('slug');

        // Get World
        $data['world'] = $this->world_model->get_world_by_slug($slug);
        if ($data['world']) {
            redirect(base_url() . $slug, 'refresh');
        }

        // Set inputs
        $data = array();
        $data['slug'] = $slug;
        $data['user_key'] = $user['id'];
        $data['archived'] = 0;

        // Insert world
        $this->world_model->insert_world($data);

        // Redirect to homepage
        redirect(base_url() . 'w/' . $slug, 'refresh');
    }

    public function load_map_rooms()
    {
        // Get filters
        $data['filters'] = $this->get_filters();

        // Use last activity default first
        $data['current_last_activity_filter'] = $data['filters'][LAST_ACTIVITY_DEFAULT];

        // Get last activity filter if exists
        if ($this->input->get('last_activity') && isset($data['filters'][$this->input->get('last_activity')])) {
            $data['current_last_activity_filter'] = $data['filters'][$this->input->get('last_activity')];
        }

        // Get rooms by last activity
        $data['rooms'] = $this->room_model->get_all_rooms_by_last_activity($data['current_last_activity_filter']['minutes_ago'], $this->input->get('world_id'));

        // Return rooms
        echo api_response($data['rooms']);
    }

    public function guess_location()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $api_response = @file_get_contents("http://ipinfo.io/{$ip}");
        if (!$api_response) {
            return '';
        }
        $location_guess = json_decode($api_response);
        $location_prepopulate = '';
        if (isset($location_guess->region) && $location_guess->region && isset($location_guess->country) && $location_guess->country) {
            $location_prepopulate = $location_guess->region . ', ' . $location_guess->country;
        }
        return $location_prepopulate;
    }

    public function get_filters()
    {
        $filters = array();
        $filters['all'] = array(
            'slug' => 'all',
            'minutes_ago' => 5 * 365 * 24 * 60,
        );
        $filters['this_week'] = array(
            'slug' => 'this_week',
            'minutes_ago' => 7 * 24 * 60,
        );
        $filters['today'] = array(
            'slug' => 'today',
            'minutes_ago' => 1 * 24 * 60,
        );
        $filters['this_hour'] = array(
            'slug' => 'this_hour',
            'minutes_ago' => 1 * 60,
        );
        $filters['now'] = array(
            'slug' => 'now',
            'minutes_ago' => 20,
        );
        return $filters;
    }
}
