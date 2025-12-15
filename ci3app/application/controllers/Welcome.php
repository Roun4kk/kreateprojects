<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index()
    {   
	
        $this->load->helper('url');
        $data['page_title'] = 'Welcome to CodeIgniter';
        $this->load->view('header', $data);
        $this->load->view('welcome_message');
        $this->load->view('footer');
    }
}