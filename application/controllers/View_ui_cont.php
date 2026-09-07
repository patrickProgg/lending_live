<?php
defined('BASEPATH') or exit('No direct script access allowed');

class View_ui_cont extends CI_Controller
{

    // -------------------------ibalik ra og mo bayad na--------------------
    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Manila');
        $this->db->query("SET time_zone = '+08:00'");

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    // -------------------------ibalik ra og mo bayad na--------------------

    // function __construct()
    // {
    //     parent::__construct();

    //     date_default_timezone_set('Asia/Manila');
    //     $this->db->query("SET time_zone = '+08:00'");

    //     // Get the current controller/method
    //     $controller = $this->router->fetch_class();
    //     $method = $this->router->fetch_method();

    //     // Allow access to maintenance and login without checking session
    //     if ($method == 'maintenance' || $controller == 'Login_cont') {
    //         return;
    //     }

    //     // Check for logged_in
    //     if (!$this->session->userdata('logged_in')) {
    //         redirect('login');
    //     }
    // }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $this->load->view('layouts/header');
        $this->load->view('dashboard');
        $this->load->view('layouts/footer');
    }
    public function monitoring()
    {
        $this->load->view('layouts/header');
        $this->load->view('monitoring');
        $this->load->view('layouts/footer');
    }

    public function pull_out()
    {
        $this->load->view('layouts/header');
        $this->load->view('pull_out');
        $this->load->view('layouts/footer');
    }

    public function expenses()
    {
        $this->load->view('layouts/header');
        $this->load->view('expenses');
        $this->load->view('layouts/footer');
    }

    public function history()
    {
        $this->load->view('layouts/header');
        $this->load->view('history');
        $this->load->view('layouts/footer');
    }

    // -------------------------e delete ra og mo bayad na--------------------    
    public function maintenance()
    {
        $this->load->view('maintenance');
    }
    public function subscription()
    {
        $this->load->view('subscription');
    }
    // -------------------------e delete ra og mo bayad na--------------------

}
