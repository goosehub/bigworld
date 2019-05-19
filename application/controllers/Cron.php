<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('main_model', '', TRUE);
        $this->load->model('room_model', '', TRUE);

        $this->main_model->record_request();
    }

    // Map view
    public function index($token = false)
    {
        // Use hash equals function to prevent timing attack
        $auth = auth();
        if (!$token) {
            return false;
        }
        if (!hash_equals($auth->token, $token)) {
            return false;
        }

        echo 'Start of Cron - ' . time() . '<br>';

        $this->trim_inactive_rooms();

        echo 'End of Cron - ' . time() . '<br>';
    }

    public function trim_inactive_rooms()
    {
        $crontab = '* * * * *'; // Every minute
        $now = date('Y-m-d H:i:s');
        $run_crontab = parse_crontab($now, $crontab);
        if (!$run_crontab) {
            return false;
        }

        echo 'trim_inactive_rooms - ' . time() . '<br>';
        $this->room_model->archive_inactive_rooms(ROOM_TRIM_MINUTES_SINCE_LAST_MESSAGE);
    }

}