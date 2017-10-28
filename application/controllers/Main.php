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

        $data['current_last_activity_filter'] = (int) $this->input->get('last_activity');
        if ($this->input->get('last_activity')) {
            $data['rooms'] = $this->room_model->get_all_rooms_by_last_activity($data['current_last_activity_filter']);
        }
        else {
            $data['rooms'] = $this->room_model->get_all_rooms();
        }

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

    public function guess_location()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (is_dev()) {
            $ip = '3.62.232.229';
        }
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
            'last_activity_in_minutes' => 0, // 0 evaluated to false
        );
        $filters['active_this_week'] = array(
            'slug' => 'active_this_week',
            'last_activity_in_minutes' => 7 * 24 * 60,
        );
        $filters['active_today'] = array(
            'slug' => 'active_today',
            'last_activity_in_minutes' => 1 * 24 * 60,
        );
        $filters['active_this_hour'] = array(
            'slug' => 'active_this_hour',
            'last_activity_in_minutes' => 1 * 60,
        );
        $filters['currently_active'] = array(
            'slug' => 'currently_active',
            'last_activity_in_minutes' => 15,
        );
        return $filters;
    }
}
