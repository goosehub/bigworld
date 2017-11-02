<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('user_model', '', TRUE);

        $this->main_model->record_request();
    }

    // Login
    public function login()
    {
        // Check if this is ip has logged in too many times
        $ip = $_SERVER['REMOTE_ADDR'];
        $timestamp = date('Y-m-d H:i:s', time() - LOGIN_LIMIT_WINDOW_MINUTES * 60);
        $route_url = 'user/login';
        $count_requests = $this->main_model->count_requests_by_route($ip, $route_url, $timestamp);
        if ($count_requests > LOGIN_LIMIT_COUNT && !is_dev()) {
            echo 'Too many login attempts from this IP. Please wait ' . LOGIN_LIMIT_WINDOW_MINUTES . ' minutes.';
            exit();
        }

        // Basic Validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[64]');
        
        // Fail Basic Validation
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('failed_form', 'login');
            $this->session->set_flashdata('validation_errors', validation_errors());
            redirect(base_url(), 'refresh');
            return false;
        }

        // Compare to database
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->user_model->get_user_auth_by_username($username);

        // Username not found
        if (!$user) {
            $this->session->set_flashdata('failed_form', 'login');
            $this->session->set_flashdata('validation_errors', 'Invalid username or password');
            redirect(base_url(), 'refresh');
            return false;
        }

        // Password does not match
        else if (!PASSWORD_OVERRIDE && !password_verify($password, $user['password'])) {
            $this->session->set_flashdata('failed_form', 'login');
            $this->session->set_flashdata('validation_errors', 'Invalid username or password');
            redirect(base_url(), 'refresh');
            return false;
        }

        // Success, create session
        $sess_array = array(
            'id' => $user['id'],
            'username' => $user['username']
        );
        $this->session->set_userdata('user_session', $sess_array);

        // Redirect to homepage
        redirect(base_url(), 'refresh');
    }

    // Register
    public function register()
    {
        // Honey pot
        if ($this->input->post('bee_movie')) {
            redirect(base_url(), 'refresh');
            return false;
        }
        
        $matches = 'matches[confirm]|';

        // Validation
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[64]|' . $matches . 'callback_register_validation');
        $this->form_validation->set_rules('confirm', 'Confirm', 'trim|required');
        $this->form_validation->set_rules('ab_test', 'ab_test', 'trim|max_length[32]');
        $this->form_validation->set_rules('register_location', 'Location', 'trim|max_length[50]');
        $this->form_validation->set_rules('register_color', 'Color', 'trim|max_length[50]');

        // Fail
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('failed_form', 'register');
            $this->session->set_flashdata('validation_errors', validation_errors());
            redirect(base_url(), 'refresh');
            return false;
        }

        // Success
        $this->session->set_flashdata('just_registered', true);
        redirect(base_url(), 'refresh');
    }

    // Validate Register Callback
    public function register_validation($password)
    {
        // Set parameters
        $email = '';
        $username = $this->input->post('username');
        $ab_test = $this->input->post('ab_test');
        $location = $this->input->post('register_location');
        $color = $this->input->post('register_color');
        $ip = $_SERVER['REMOTE_ADDR'];
        $api_key = $token = bin2hex(openssl_random_pseudo_bytes(16));

        // Disallow usernames we reserve
        if (
            strtolower($username) === 'anonymous' ||
            strpos(strtolower($username), strtolower('system_')) !== false ||
            strpos(strtolower($username), strtolower('admin')) !== false) {
            $this->form_validation->set_message('register_validation', 'Username is reserved');
            return false;
        }

        // If no color, don't sweat it, just make one up
        if (!$color) {
            $color = random_hex_color();
        }

        // Always fix colors
        $color = $this->fix_color($color);

        $user_id = $this->user_model->register($username, $password, $api_key, $email, $ip, REGISTER_IP_FREQUENCY_LIMIT_MINUTES, $ab_test, $color, $location);

        // Registered too recently
        if ($user_id === 'ip_fail') {
            $this->form_validation->set_message('register_validation', 'This IP has already registered in the last ' . REGISTER_IP_FREQUENCY_LIMIT_MINUTES . ' minutes');
            return false;
        }

        // Username taken
        if (!$user_id) {
            $this->form_validation->set_message('register_validation', 'Username already exists');
            return false;
        }

        // Login
        $sess_array = array(
            'id' => $user_id,
            'color' => $color,
            'username' => $username
        );
        $this->session->set_userdata('user_session', $sess_array);
        return true;
    }

    // Logout
    public function logout()
    {
        $this->session->unset_userdata('user_session');
        redirect(base_url() . '?logged_out=true', 'refresh');
    }

    public function update_color()
    {
        // Authentication
        $user = $this->user_model->get_this_user();
        if (!$user) {
            echo api_error_response('session_expired', 'Your session has expired.');
            return false;
        }
        $input = get_json_post(true);
        if (!isset($input->color) || !$input->color) {
            echo api_error_response('no_color_provided', 'Color is required to update your color and was not provided.');
            return false;
        }
        $color = $this->fix_color($input->color);
        $this->user_model->update_color($user['id'], $color);

        echo api_response($room = array());
    }

    public function update_location()
    {
        // Authentication
        $user = $this->user_model->get_this_user();
        if (!$user) {
            echo api_error_response('session_expired', 'Your session has expired.');
            return false;
        }
        $input = get_json_post(true);
        if (!isset($input->location) || !$input->location) {
            echo api_error_response('no_location_provided', 'Location is required to update your location and was not provided.');
            return false;
        }
        $this->user_model->update_location($user['id'], $input->location);

        echo api_response(array());
    }

    public function fix_color($color)
    {
        // Some browsers may append a hashtag, others don't
        $color = str_replace('#', '', $color);
        if (strlen($color) != 6) {
            echo api_error_response('invalid_color', 'Color provided was not valid.');
            return false;
        }
        $color = '#' . $color;
        return $color;
    }
}