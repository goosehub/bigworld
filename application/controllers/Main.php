<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);

        $this->main_model->record_request();
    }

    public function index()
    {
        // Authentication
        $data['user'] = $this->user_model->get_this_user();

        // Get filters
        $data['filters'] = $this->get_filters();

        if ($data['user']) {
            // Include owned rooms
            $data['user']['rooms'] = $this->room_model->get_rooms_by_user_key($data['user']['id']);
            
            // Include favorited rooms
            $data['user']['favorites'] = $this->room_model->get_favorites_by_user_key($data['user']['id']);
        }

        // Use last activity default first
        $data['current_last_activity_filter'] = $data['filters'][LAST_ACTIVITY_DEFAULT];

        // Get last activity filter if exists
        if ($this->input->get('last_activity') && isset($data['filters'][$this->input->get('last_activity')])) {
            $data['current_last_activity_filter'] = $data['filters'][$this->input->get('last_activity')];
        }

        // Get rooms by last activity
        $data['rooms'] = $this->room_model->get_all_rooms_by_last_activity($data['current_last_activity_filter']['minutes_ago']);

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
        $data['page_title'] = site_name();
        $this->load->view('templates/header', $data);
        $this->load->view('main', $data);
        $this->load->view('menus', $data);
        $this->load->view('blocks', $data);
        $this->load->view('room', $data);
        $this->load->view('scripts/map_script', $data);
        $this->load->view('scripts/chat_script', $data);
        $this->load->view('scripts/interface_script', $data);
        $this->load->view('templates/footer', $data);
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
        $data['rooms'] = $this->room_model->get_all_rooms_by_last_activity($data['current_last_activity_filter']['minutes_ago']);

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
